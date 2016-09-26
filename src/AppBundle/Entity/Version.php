<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Version
 *
 * @ORM\Table(name="version")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VersionRepository")
 */
class Version
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
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;

    /**
     * @var int
     *
     * @ORM\Column(name="gif_id", type="integer")
     */
    private $gifId;

    /**
     * @var int
     *
     * @ORM\Column(name="ad_id", type="integer")
     */
    private $adId;


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
     * Set hash
     *
     * @param string $hash
     *
     * @return Version
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set gifId
     *
     * @param integer $gifId
     *
     * @return Version
     */
    public function setGifId($gifId)
    {
        $this->gifId = $gifId;

        return $this;
    }

    /**
     * Get gifId
     *
     * @return int
     */
    public function getGifId()
    {
        return $this->gifId;
    }

    /**
     * Set adId
     *
     * @param integer $adId
     *
     * @return Version
     */
    public function setAdId($adId)
    {
        $this->adId = $adId;

        return $this;
    }

    /**
     * Get adId
     *
     * @return int
     */
    public function getAdId()
    {
        return $this->adId;
    }
}
