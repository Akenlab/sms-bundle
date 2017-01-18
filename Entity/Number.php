<?php

namespace Akenlab\SMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Number
 *
 * @ORM\Table(name="number")
 * @ORM\Entity(repositoryClass="Akenlab\SMSBundle\Repository\NumberRepository")
 */
class Number
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var int
     *
     * @ORM\Column(name="rambleOnCounter", type="integer", nullable=true)
     */
    private $rambleOnCounter;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=15, unique=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=50)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255, nullable=true)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var bool
     *
     * @ORM\Column(name="valid", type="boolean", nullable=true)
     */
    private $valid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastInteractionDate", type="datetime", nullable=true)
     */
    private $lastInteractionDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastSentDate", type="datetime", nullable=true)
     */
    private $lastSentDate;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastReceivedDate", type="datetime", nullable=true)
     */
    private $lastReceivedDate;


    /**
     * @var string
     *
     * @ORM\Column(name="lastReceived", type="string", nullable=true)
     */
    private $lastReceived;

    /**
     * @var string
     *
     * @ORM\Column(name="lastSent", type="string", nullable=true)
     */
    private $lastSent;

    /**
     * @var \DateTime
     */
    private $lastSentAge;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="number")
     */
    private $messages;


    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->state="base";
        $this->pseudo="";
    }

    public function __toString()
    {
        if($this->pseudo !== ""){
            return $this->pseudo;
        }
        return $this->number;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Number
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Number
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set label
     *
     * @param string $pseudo
     *
     * @return Number
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Number
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Number
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     *
     * @return Number
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return bool
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Get messages
     *
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set lastInteractionDate
     *
     * @param \DateTime $lastInteractionDate
     *
     * @return Number
     */
    public function setLastInteractionDate($lastInteraction)
    {
        $this->lastInteractionDate = $lastInteraction;

        return $this;
    }

    /**
     * Get lastInteractionDate
     *
     * @return \DateTime
     */
    public function getLastInteractionDate()
    {
        return $this->lastInteractionDate;
    }

    /**
     * Set lastSentDate
     *
     * @param \DateTime $lastSentDate
     *
     * @return Number
     */
    public function setLastSentDate($lastSentDate)
    {
        $this->lastSentDate = $lastSentDate;

        return $this;
    }

    /**
     * Get lastSentDate
     *
     * @return \DateTime
     */
    public function getLastSentDate()
    {
        return $this->lastSentDate;
    }

    /**
     * Set lastReceivedDate
     *
     * @param \DateTime $lastReceivedDate
     *
     * @return Number
     */
    public function setLastReceivedDate($lastReceivedDate)
    {
        $this->lastReceivedDate = $lastReceivedDate;

        return $this;
    }

    /**
     * Get lastReceivedDate
     *
     * @return \DateTime
     */
    public function getLastReceivedDate()
    {
        return $this->lastReceivedDate;
    }

    /**
     * Set lastReceived
     *
     * @param string $lastReceived
     *
     * @return Number
     */
    public function setLastReceived($message)
    {
        if($message && $message == $this->lastReceived){
            $this->addRambleOn();
        }else{
            $this->setRambleOnCounter(max(0,$this->rambleOnCounter-1));
        }
        $this->lastReceived = $message;
        $this->setLastReceivedDate(new \DateTime());
        $this->setLastInteractionDate(new \DateTime());

        return $this;
    }

    /**
     * Get lastReceived
     *
     * @return string
     */
    public function getLastReceived()
    {
        return $this->lastReceived;
    }

    /**
     * Set lastSent
     *
     * @param string $lastSent
     *
     * @return Number
     */
    public function setLastSent($message)
    {
        if($message && $message == $this->lastSent){
            $this->addRambleOn(2);
        }else{
            $this->setRambleOnCounter(max(0,$this->rambleOnCounter-1));
        }
        $this->lastSent = $message;
        $this->setLastSentDate(new \DateTime());
        $this->setLastInteractionDate(new \DateTime());

        return $this;
    }

    /**
     * Get lastSent
     *
     * @return string
     */
    public function getLastSent()
    {
        return $this->lastSent;
    }


    private function addRambleOn($qty=1)
    {
        $this->setRambleOnCounter($this->getRambleOnCounter()+$qty);
    }

    /**
     * Set rambleOnCounter
     *
     * @param integer $RambleOnCounter
     *
     * @return Number
     */
    public function setRambleOnCounter($counter)
    {
        $this->rambleOnCounter = $counter;

        return $this;
    }

    /**
     * Get rambleOnCounter
     *
     * @return integer
     */
    public function getRambleOnCounter()
    {
        return $this->rambleOnCounter;
    }
}
