<?php

namespace TinyUrl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TinyUrl\MainBundle\Helpers\StringUtils;

/**
 * Link
 *
 * @ORM\Table(name="link")
 * @ORM\Entity(repositoryClass="TinyUrl\MainBundle\Repository\LinkRepository")
 */
class Link
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
     * @ORM\Column(name="long_url", type="string", length=255, unique=true)
     */
    private $longUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="short_code", type="string", length=255, unique=true)
     */
    private $shortCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="counter", type="integer")
     */
    private $counter;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setCounter(0);
        $this->setShortCode(StringUtils::generateRandomString());
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
     * Set longUrl
     *
     * @param string $longUrl
     *
     * @return Link
     */
    public function setLongUrl($longUrl)
    {
        $this->longUrl = $longUrl;

        return $this;
    }

    /**
     * Get longUrl
     *
     * @return string
     */
    public function getLongUrl()
    {
        return $this->longUrl;
    }

    /**
     * Set shortCode
     *
     * @param string $shortCode
     *
     * @return Link
     */
    public function setShortCode($shortCode)
    {
        $this->shortCode = $shortCode;

        return $this;
    }

    /**
     * Get shortCode
     *
     * @return string
     */
    public function getShortCode()
    {
        return $this->shortCode;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Link
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set counter
     *
     * @param integer $counter
     *
     * @return Link
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return int
     */
    public function getCounter()
    {
        return $this->counter;
    }
}