<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={
 *  @ORM\UniqueConstraint(name="url_idx", columns={"url"})
 * })
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
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $area;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\Column(type="string")
     */
    private $thumbUrl = '';

    /**
     * @ORM\Column(type="text")
     */
    private $description = '';

    /**
     * @ORM\Column(type="text", options={"default": ""})
     */
    private $comment = '';

    /**
     * @ORM\Column(type="array")
     */
    private $pictures = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $includingCharges;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFurnished = false;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    private $ignored = false;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    private $starred = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public static function createFromArray(array $data): Offer
    {
        $offer = new static();

        $offer->url = $data['url'];
        $offer->title = $data['title'];
        $offer->price = (int) $data['price'];
        $offer->area = (int) $data['area'];
        $offer->rooms = (int) $data['rooms'];
        $offer->thumbUrl = $data['thumb_url'];
        $offer->description = $data['description'];
        $offer->includingCharges = (bool) $data['is_charges_included'];
        $offer->isFurnished = (bool) $data['is_furnished'];
        $offer->createdAt = $data['created_at'] ?? $offer->createdAt;

        return $offer;
    }

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function rooms(): int
    {
        return $this->rooms;
    }

    public function thumb(): string
    {
        return $this->thumbUrl;
    }

    public function pictures(): array
    {
        return $this->pictures;
    }

    public function isIncludingCharges(): bool
    {
        return $this->includingCharges;
    }

    public function isFurnished(): bool
    {
        return $this->isFurnished;
    }

    public function area(): int
    {
        return $this->area;
    }

    public function isIgnored(): bool
    {
        return $this->ignored;
    }

    public function ignore(): void
    {
        $this->ignored = true;
    }

    public function isStarred(): bool
    {
        return $this->starred;
    }

    public function star(): void
    {
        $this->starred = true;
    }

    public function unStar(): void
    {
        $this->starred = false;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function comment(): string
    {
        return $this->comment;
    }

    public function updateComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
