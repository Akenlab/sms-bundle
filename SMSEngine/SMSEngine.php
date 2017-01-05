<?php

namespace SMSBundle\SMSEngine;

use SMSBundle\Entity\Number;
use SMSBundle\Entity\Response;

use Twilio\Rest\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twilio\Exceptions\RestException;
use SMSBundle\Utility\StringVariation;

class SMSEngine
{
	private $sid;
	private $token;
	private $client;
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
	}

    public function respond( $body, Number $number)
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
	    	$this->sendSMS(StringVariation::fetch($response->getBody()), $number->getNumber());
	    }
	    if($response->getTransition()){
	        $workflow->apply($number, $response->getTransition());
	    }

       	return $response;
    }


	public function sendSMS($body, $recipient){
//		return true;
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
        	$rawnumber=$this->client->lookups->phoneNumbers($rawNumber)->fetch(array("PhoneNumber"))->phoneNumber;
        	$number=new Number();
	        $number->setNumber($rawNumber);
	        $this->em->persist($number);
	        $this->em->flush();
	        return $number;
        } catch (RestException $e) {
        	throw new \Exception("Unreachable number", 1);
        }
	}
}
?>