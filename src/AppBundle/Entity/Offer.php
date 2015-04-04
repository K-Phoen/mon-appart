<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={
 *  @ORM\UniqueConstraint(name="url_idx", columns={"url"})
 * })
 */
class Offer
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $url;

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

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    private $starred = false;

    /**
     * @ORM\Column(type="text", options={"default" = ""})
     */
    private $comment = '';

    public static function createFromArray(array $data)
    {
        $offer = new static();
        $offer->generateUuid();

        if (!empty($data['url'])) {
            $offer->url = $data['url'];
        }

        if (!empty($data['origin'])) {
            $offer->origin = $data['origin'];
        }

        if (!empty($data['title'])) {
            $offer->title = $data['title'];
        }

        if (!empty($data['price'])) {
            $offer->price = $data['price'];
        }

        if (!empty($data['area'])) {
            $offer->area = $data['area'];
        }

        if (!empty($data['rooms'])) {
            $offer->rooms = $data['rooms'];
        }

        if (!empty($data['thumb'])) {
            $offer->thumb = $data['thumb'];
        }

        if (!empty($data['pictures'])) {
            $offer->pictures = $data['pictures'];
        }

        if (!empty($data['city'])) {
            $offer->city = $data['city'];
        }

        if (!empty($data['zip_code'])) {
            $offer->zip_code = $data['zip_code'];
        }

        if (!empty($data['description'])) {
            $offer->description = $data['description'];
        }

        if (array_key_exists('including_charges', $data)) {
            $offer->including_charges = $data['including_charges'];
        }

        if (array_key_exists('includes_furnitures', $data)) {
            $offer->includes_furnitures = $data['includes_furnitures'];
        }

        return $offer;
    }

    public function generateUuid()
    {
        $this->id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function flagAsViewed()
    {
        $this->viewed = true;
    }

    public function setStarred($starred)
    {
        $this->starred = $starred;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
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
     * Get url
     *
     * @return string Url
     */
    public function getUrl()
    {
        return $this->url;
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * Get thumb
     *
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
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
     * Get including_charges
     *
     * @return boolean
     */
    public function getIncludingCharges()
    {
        return $this->including_charges;
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
     * Get area
     *
     * @return integer
     */
    public function getArea()
    {
        return $this->area;
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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get starred
     *
     * @return boolean
     */
    public function getStarred()
    {
        return $this->starred;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}
