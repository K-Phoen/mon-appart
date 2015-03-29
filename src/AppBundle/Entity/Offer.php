<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $origin;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $area;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $zip_code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rooms;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $thumb = '';

    /**
     * @ORM\Column(type="text")
     */
    private $description = '';

    /**
     * @ORM\Column(type="array")
     */
    private $pictures = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $including_charges;

    /**
     * @ORM\Column(type="boolean")
     */
    private $includes_furnitures = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $viewed = false;

    public static function fromArray(array $data)
    {
        $offer = new static();

        if (!empty($data['id'])) {
            $offer->setId($data['id']);
        }

        if (!empty($data['origin'])) {
            $offer->setOrigin($data['origin']);
        }

        if (!empty($data['title'])) {
            $offer->setTitle($data['title']);
        }

        if (!empty($data['price'])) {
            $offer->setPrice($data['price']);
        }

        if (!empty($data['area'])) {
            $offer->setArea($data['area']);
        }

        if (!empty($data['rooms'])) {
            $offer->setRooms($data['rooms']);
        }

        if (!empty($data['thumb'])) {
            $offer->setThumb($data['thumb']);
        }

        if (!empty($data['pictures'])) {
            $offer->setPictures($data['pictures']);
        }

        if (!empty($data['city'])) {
            $offer->setCity($data['city']);
        }

        if (!empty($data['zip_code'])) {
            $offer->setZipCode($data['zip_code']);
        }

        if (!empty($data['zip_code'])) {
            $offer->setZipCode($data['zip_code']);
        }

        if (!empty($data['description'])) {
            $offer->setDescription($data['description']);
        }

        if (array_key_exists('including_charges', $data)) {
            $offer->setIncludingCharges($data['including_charges']);
        }

        if (array_key_exists('includes_furnitures', $data)) {
            $offer->setIncludesFurnitures($data['includes_furnitures']);
        }

        return $offer;
    }

    public function getUrl()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Offer
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return Offer
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Offer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Offer
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Offer
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zip_code
     *
     * @param string $zipCode
     * @return Offer
     */
    public function setZipCode($zipCode)
    {
        $this->zip_code = $zipCode;

        return $this;
    }

    /**
     * Get zip_code
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Offer
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set rooms
     *
     * @param integer $rooms
     * @return Offer
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Get rooms
     *
     * @return integer
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set thumb
     *
     * @param string $thumb
     * @return Offer
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * Get thumb
     *
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * Set pictures
     *
     * @param array $pictures
     * @return Offer
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Get pictures
     *
     * @return array
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set including_charges
     *
     * @param boolean $includingCharges
     * @return Offer
     */
    public function setIncludingCharges($includingCharges)
    {
        $this->including_charges = $includingCharges;

        return $this;
    }

    /**
     * Get including_charges
     *
     * @return boolean
     */
    public function getIncludingCharges()
    {
        return $this->including_charges;
    }

    /**
     * Set includes_furnitures
     *
     * @param boolean $includesFurnitures
     * @return Offer
     */
    public function setIncludesFurnitures($includesFurnitures)
    {
        $this->includes_furnitures = $includesFurnitures;

        return $this;
    }

    /**
     * Get includes_furnitures
     *
     * @return boolean
     */
    public function getIncludesFurnitures()
    {
        return $this->includes_furnitures;
    }

    /**
     * Set area
     *
     * @param integer $area
     * @return Offer
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return integer
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set viewed
     *
     * @param boolean $viewed
     * @return Offer
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * Get viewed
     *
     * @return boolean
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
