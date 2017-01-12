<?php

namespace Akenlab\SMSBundle\SMSEngine;

use Akenlab\SMSBundle\Entity\Number;
use Akenlab\SMSBundle\Entity\Message;
use Akenlab\SMSBundle\Entity\Response;
use Akenlab\SMSBundle\Event\NumberRegisteredEvent;

use Twilio\Rest\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twilio\Exceptions\RestException;
use Akenlab\SMSBundle\Utility\StringVariation;
use Symfony\Component\EventDispatcher\EventDispatcher;


class SMSEngine
{
	private $sid;
	private $token;
	private $client;
	private $logger;
    protected $container;
    protected $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
		$this->initClient();
    }

	private function initClient()
	{
		$this->sid=$this->container->getParameter('sms.twilio.sid');
		$this->token=$this->container->getParameter('sms.twilio.token');
		$this->sender=$this->container->getParameter('sms.twilio.sender');
		$this->client = new Client($this->sid, $this->token);
		$this->logger = $this->container->get('logger');

	}

    public function respond( $body, Number $number, $alwaysAnswer=true)
    {
        
        $response=new Response();

        // Is the message an acceptable answer for the current state ?
        $repoIn = $this->em->getRepository('SMSBundle:InboundMessage');
        $inboundMessage=$repoIn->findOneBy(array("numberState"=>$number->getState(),"body"=>$body));

        $workflow = $this->container->get('state_machine.numbers_state');
        $enabledTransitions=array();
        foreach($workflow->getEnabledTransitions($number) as $transition)
        {
        	$enabledTransitions[]=$transition->getName();
        }
        if($inboundMessage)
        {
	        if($inboundMessage->getResponse()){
	        	if(in_array($inboundMessage->getResponse()->getTransition(),$enabledTransitions)){
		        	$response=$inboundMessage->getResponse();
	        	}
	        } 
        }else{// This answer is unkwown in this context
        	$possibleAnswers=array();
        	$possibleInbounds=$repoIn->findBy(array("numberState"=>$number->getState()));
        	foreach($possibleInbounds as $inbound){
        		$possibleAnswers[]=$inbound->getBody();
        	}
        	$response=new Response();
	        if(count($possibleAnswers)){
		        $response->setBody("Vous pouvez répondre par : ".implode(", ",$possibleAnswers));
	        }else{
		        $response->setBody("evasive.answer");
	        }
        }
        if($number->getRambleOnCounter()>2){
        	$response=new Response();
	        $response->setBody("deaf.dialogue");
        }
        if($number->getRambleOnCounter()>3){
        	$response=new Response();
	        $response->setBody(null);
        }
	    if($response->getBody() !== null){
	    	if($response->getBody() !== "evasive.answer" || $alwaysAnswer){
	    		$body=StringVariation::fetch($response->getBody());
		    	$this->sendSMS($body, $number->getNumber());

		        $message=$this->storeMessage($body,$number,"outbound");
		        $this->em->persist($message);

		    	$number->setLastSent($response->getBody());
		    }
	    }
	    if($response->getTransition()){
	        $workflow->apply($number, $response->getTransition());
	    }

       	return $response;
    }


	public function sendSMS($body, $recipient){
		$debug = $this->container->get('kernel')->isDebug();
		if($debug){
			$this->logger->info("Sent SMS : \"".$body."\" to ".$recipient);
			return true;
		}
		$this->logger->info("Send SMS to ".$recipient);

		return $this->client->messages->create(
		    $recipient,
		    array(
		        'from' => $this->sender,
		        'body' => $body
		    )
		);
	}

	/**
	* @param string $number Phone Number
    * @return \Twilio\ListResource The requested resource
    * @throws 
	*/
	public function validateNumber($rawNumber){ 
        try{
        	$rawNumber=$this->client->lookups->phoneNumbers($rawNumber)->fetch(array("PhoneNumber"))->phoneNumber;
        	$number=new Number();
	        $number->setNumber($rawNumber);
	        $number->setState("base");
	        //$this->em->persist($number);
	        //$this->em->flush();
	        $event = new NumberRegisteredEvent($number);
			$dispatcher = $this->container->get('event_dispatcher');
			$dispatcher->dispatch(NumberRegisteredEvent::NAME, $event);
    		$this->logger->info("New number registered :".$rawNumber);
	        return $number;
        } catch (RestException $e) {
        	throw new \Exception("Unreachable number", 1);
        }
	}

	public function storeMessage($body,$number,$direction){
        $message=new Message();
        $message->setBody($body);
        $message->setDate(new \DateTime());
        $message->setNumber($number);
        $message->setDirection($direction);
        return $message;
	}

}
?>