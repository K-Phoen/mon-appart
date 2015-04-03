<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Offer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $offers = $this->get('repository.offer')->findBy([
            'viewed' => false,
        ], [
            'starred'   => 'DESC',
            'createdAt' => 'DESC',
            'id'        => 'DESC',
        ]);

        return $this->render('AppBundle:Default:index.html.twig', [
            'search_criteria' => $this->container->getParameter('app.search.criteria'),
            'offers'          => $offers,
        ]);
    }

    public function flagAsViewAction(Request $request)
    {
        $offer = $this->getOffer($request->query->get('id'));
        $offer->setViewed(true);

        return $this->saveOfferAndGoHome($offer);
    }

    public function starAction(Request $request)
    {
        $offer = $this->getOffer($request->query->get('id'));
        $offer->setStarred(!$request->attributes->get('_unstar', false));

        return $this->saveOfferAndGoHome($offer);
    }

    public function commentAction(Request $request)
    {
        $offer = $this->getOffer($request->request->get('offer'));
        $offer->setComment($request->request->get('comment', ''));

        return $this->saveOfferAndGoHome($offer);
    }

    private function saveOfferAndGoHome(Offer $offer)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->persist($offer);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    private function getOffer($id)
    {
        $offer = $this->get('repository.offer')->find($id);

        if ($offer === null) {
            throw $this->createNotFoundException('Offer not found.');
        }

        return $offer;
    }
}
