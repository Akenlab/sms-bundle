<?php

namespace SMSBundle\Utility;


class StringVariation
{
    public static function fetch($string)
    {
        $variations=array();
        switch ($string){
        	case "evasive.answer":
        		$variations[]="Je ne sais que vous dire...";
        		$variations[]="...C'est pas faux";
        		$variations[]="Je ne sais que répondre...";
        		break;
        	case "deaf.dialogue":
        		$variations[]="Visiblement, nous ne nous comprenons pas : arrêtons cette conversation :-)";
        		$variations[]="Êtes vous sûr de vouloir continuer cette discussion ?";
        		break;
        	default:
        		$variations=array($string);
        		break;
        }
        return $variations[array_rand($variations)];
    }
}
?>