<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/", name="list_offers")
     */
    public function listOffers()
    {
        return $this->render('offers/list.html.twig');
    }
}
