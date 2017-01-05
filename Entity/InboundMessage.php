<?php

namespace SMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InboundMessage
 *
 * @ORM\Table(name="inbound_message")
 * @ORM\Entity(repositoryClass="SMSBundle\Repository\InboundMessageRepository")
 */
class InboundMessage
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
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=255)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="numberState", type="string", length=50)
     */
    private $numberState;

    /**
     * @ORM\ManyToOne(targetEntity="Response")
     * @ORM\JoinColumn(name="response_id", referencedColumnName="id")
     */
    private $response;
    

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
     * Set body
     *
     * @param string $body
     *
     * @return InboundMessage
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set numberState
     *
     * @param string $numberState
     *
     * @return InboundMessage
     */
    public function setNumberState($numberState)
    {
        $this->numberState = $numberState;

        return $this;
    }

    /**
     * Get numberState
     *
     * @return string
     */
    public function getNumberState()
    {
        return $this->numberState;
    }

    /**
     * Set response
     *
     * @param \SMSBundle\Entity\Response $response
     *
     * @return InboundMessage
     */
    public function setResponse(\SMSBundle\Entity\Response $response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return \SMSBundle\Entity\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
