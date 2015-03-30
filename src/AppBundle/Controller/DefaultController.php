<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $offers = $this->get('repository.offer')->findBy([
            'viewed' => false,
        ], [
            'starred'   => 'DESC',
            'createdAt' => 'DESC',
        ]);

        return $this->render('AppBundle:Default:index.html.twig', [
            'offers' => $offers,
        ]);
    }

    public function flagAsViewAction(Request $request)
    {
        $offer = $this->getOffer($request->query->get('id'));
        $offer->setViewed(true);

        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->persist($offer);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    public function starAction(Request $request)
    {
        $offer = $this->getOffer($request->query->get('id'));
        $offer->setStarred(!$request->attributes->get('_unstar', false));

        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->persist($offer);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    public function commentAction(Request $request)
    {
        $offer = $this->getOffer($request->request->get('offer'));
        $offer->setComment($request->request->get('comment', ''));

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
