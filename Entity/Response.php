<?php

namespace SMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response
 *
 * @ORM\Table(name="response")
 * @ORM\Entity(repositoryClass="SMSBundle\Repository\ResponseRepository")
 */
class Response
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
     * @ORM\Column(name="ttl", type="integer", nullable=true)
     */
    private $ttl;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=255)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="sourceStatus", type="string", length=50)
     */
    private $sourceStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="transition", type="string", length=50)
     */
    private $transition;

    private $genericBody;

    public function __construct()
    {
        $this->setBody('Pas compris');
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
     * Set ttl
     *
     * @param integer $ttl
     *
     * @return Response
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Get ttl
     *
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Response
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
     * Set body
     *
     * @param string $body
     *
     * @return Response
     */
    public function setGenericBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getGenericBody()
    {
        return $this->body;
    }

    /**
     * Set sourceStatus
     *
     * @param string $sourceStatus
     *
     * @return Response
     */
    public function setSourceStatus($sourceStatus)
    {
        $this->sourceStatus = $sourceStatus;

        return $this;
    }

    /**
     * Get sourceStatus
     *
     * @return string
     */
    public function getSourceStatus()
    {
        return $this->sourceStatus;
    }

    /**
     * Set transition
     *
     * @param string $transition
     *
     * @return Response
     */
    public function setTransition($transition)
    {
        $this->transition = $transition;

        return $this;
    }

    /**
     * Get transition
     *
     * @return string
     */
    public function getTransition()
    {
        return $this->transition;
    }
}
