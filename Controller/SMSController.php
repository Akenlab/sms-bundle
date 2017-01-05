<?php

namespace SMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SMSBundle\Entity\Number;

class SMSController extends Controller
{
    public function sendMultiple($body, $recipients)
    {
        foreach($recipients as $recipient){
            $this -> send($body, $recipient);
        }
        return;
    }
    public function send($body, $recipient)
    {
        $sms = $this->container->get('sms_bundle.sms');
        return $sms -> sendSMS($body, $recipient->getNumber());
    }

    /**
     * @Route("send/link")
     */
    public function SendLinkAction()
    {
        $recipients=$this->getRecipients();

        foreach($recipients as $recipient){

        }
        $this->sendMultiple('Visitez le site : http://www.google.com',$recipients);
        return $this->render('SMSBundle:SMS:send.html.twig', array(
        ));
    }
    /**
     * @Route("send/invite")
     */
    public function SendInviteAction()
    {
        $workflow = $this->container->get('state_machine.numbers_state');
        $recipients=$this->getRecipients();
        foreach($recipients as $recipient){
            if($workflow->can($recipient, 'invite')){
                $this -> send('Intéressé par le comité numérique SPIE ? Répondez "oui" à ce SMS', $recipient);
                $workflow->apply($recipient, 'invite');
                $logs[]=array("recipient"=>$recipient,"status"=>"success");
            }else{
                $this -> send('Pas possible inviter', $recipient);
                $logs[]=array("recipient"=>$recipient,"status"=>"failed (bad status)");
            }
        }
        return $this->render('SMSBundle:SMS:send.html.twig', array(
            "logs"=>$logs
        ));
    }


    /**
     * @Route("twilio/callback")
     */
    public function APICallBackAction(Request $request)
    {
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
        }
        $number->setLastReceived($body);
        $response = $smsEngine -> respond($body,$number);
        $number->setLastSent($response->getBody());
        $this->get('doctrine')->getManager()->persist($number);
        $this->get('doctrine')->getManager()->flush();

        return $this->render('SMSBundle:SMS:response.html.twig', array(
            "response"=>$response,
            "requestBody"=>$body,
        ));
    }

    public function getRecipients()
    {
        return $this->getDoctrine()
        ->getRepository('SMSBundle:Number')
        ->findAll();
    }

    public function cleanUp($message){
        return strtolower(trim($message,". \t\n\r\0\x0B"));
    }

}
