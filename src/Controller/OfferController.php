<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\OfferRepository;
use App\Spec;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            'offers' => $this->offerRepository->matching(new Spec\ToReview()),
        ]);
    }

    /**
     * @Route("/ignored", name="list_ignored_offers")
     */
    public function listIgnoredOffers()
    {
        return $this->render('offers/list.html.twig', [
            'offers' => $this->offerRepository->matching((new Spec\ToReview())->not()),
        ]);
    }

    /**
     * @Route("/{id}/ignore", name="ignore_offer", methods={"POST"})
     */
    public function ignoreOffer(string $id)
    {
        $offer = $this->offerRepository->find($id);
        if (!$offer) {
            throw $this->createNotFoundException('Offer not found.');
        }

        $offer->ignore();
        $this->offerRepository->persist($offer);

        $this->addFlash('success', 'Offre ignorée.');

        return $this->redirectToRoute('list_offers');
    }

    /**
     * @Route("/{id}/star", name="star_offer", methods={"POST"})
     */
    public function starOffer(string $id)
    {
        $offer = $this->offerRepository->find($id);
        if (!$offer) {
            throw $this->createNotFoundException('Offer not found.');
        }

        $offer->star();
        $this->offerRepository->persist($offer);

        return new Response('', Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/unstar", name="unstar_offer", methods={"POST"})
     */
    public function unstarOffer(string $id)
    {
        $offer = $this->offerRepository->find($id);
        if (!$offer) {
            throw $this->createNotFoundException('Offer not found.');
        }

        $offer->unStar();
        $this->offerRepository->persist($offer);

        return new Response('', Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/comment", name="comment_offer", methods={"POST"})
     */
    public function commentOffer(Request $request, string $id)
    {
        $offer = $this->offerRepository->find($id);
        if (!$offer) {
            throw $this->createNotFoundException('Offer not found.');
        }

        $offer->updateComment($request->request->get('comment', ''));
        $this->offerRepository->persist($offer);

        $this->addFlash('success', 'Commentaire enregistré.');

        return $this->redirectToRoute('list_offers');
    }
}
