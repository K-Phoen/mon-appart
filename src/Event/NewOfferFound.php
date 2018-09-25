<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Offer;
use Symfony\Component\EventDispatcher\Event;

class NewOfferFound extends Event
{
    private $offer;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function offer(): Offer
    {
        return $this->offer;
    }
}
