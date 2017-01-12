<?php

namespace Akenlab\SMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Akenlab\SMSBundle\Entity\Number;
use Akenlab\SMSBundle\Entity\Message;
use Akenlab\SMSBundle\Event\SMSEvent;


class SMSController extends Controller
{

    /**
     * @Route("twilio/callback")
     */
    public function APICallBackAction(Request $request)
    {
        $alwaysAnswer=true;
        
        $from=$request->request->get('From');
        if(!$from){
            throw new \Exception("Phone number is mandatory", 1);
            
        }
        
        $body=$this->cleanUp($request->request->get('Body',""));
        $number = $this ->getDoctrine()
                        ->getRepository('SMSBundle:Number')
                        ->findOneByNumber($from);
        $smsEngine = $this->container->get('sms_bundle.sms');
        if(!$number && $from){ // We don't know this number yet
            $number = $smsEngine -> validateNumber($from);
            $alwaysAnswer=false; // There will be an answer through welcome message
        }

        $message=$smsEngine -> storeMessage($request->request->get('Body',""),$number,'inbound');
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch("sms.received", new SMSEvent($request->request->get('Body',""),$number));

        $number->setLastReceived($body);
        $response = $smsEngine -> respond($body,$number,$alwaysAnswer);
        $this->get('doctrine')->getManager()->persist($number);
        $this->get('doctrine')->getManager()->flush();

        return $this->render('SMSBundle:SMS:response.html.twig', array(
            "response"=>$response,
            "requestBody"=>$body,
        ));
    }

    public function sendMultiple($body, $targetStates, $transition)
    {
        $workflow = $this->container->get('state_machine.numbers_state');
        $recipients=$this->getRecipients($targetStates);
        $logs=array();
        foreach($recipients as $recipient){
            if($workflow->can($recipient, $transition)){
                $this -> send($body, $recipient);
                $workflow->apply($recipient, $transition);
                $logs[]=array("recipient"=>$recipient,"status"=>"success");
            }else{
                $logger = $this->container->get('logger');
                $logger->info("Unable to apply '".$transition."'' to ".$recipient->getNumber());
            }
        }
        $em = $this->getDoctrine()->getManager()->flush();
    }

    public function send($body, $recipient)
    {
        $sms = $this->container->get('sms_bundle.sms');
        return $sms -> sendSMS($body, $recipient);
    }

    public function getRecipients(array $allowedStates=array())
    {
        return $this    ->getDoctrine()
                        ->getRepository('SMSBundle:Number')
                        ->findWithState($allowedStates);
    }

    public function cleanUp($message){
        return strtolower(trim($message,". \t\n\r\0\x0B"));
    }

}
