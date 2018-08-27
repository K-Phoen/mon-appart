<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /** @var OfferRepository */
    private $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    /**
     * @Route("/", name="list_offers")
     */
    public function listOffers()
    {
        return $this->render('offers/list.html.twig', [
            'offers' => $this->offerRepository->findAll(),
        ]);
    }
}
