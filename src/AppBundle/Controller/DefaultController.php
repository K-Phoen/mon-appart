<?php

namespace AppBundle\Controller;

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

    public function commentAction(Request $request, $id)
    {
        $offer = $this->getOffer($id);
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
}
