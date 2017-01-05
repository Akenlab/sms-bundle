<?php

namespace SMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $sms = $this->container->get('sms_bundle.sms');
        $answer = $sms->whoAmI();

        return $this->render('SMSBundle:Default:index.html.twig',array("who"=>$answer));
    }
}
