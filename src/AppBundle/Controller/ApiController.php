<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Offer;

class ApiController extends Controller
{
    public function flagAsViewAction($id)
    {
        $offer = $this->getOffer($id);
        $offer->flagAsViewed();

        return $this->saveAndReturn($offer, Response::HTTP_NO_CONTENT);
    }

    public function starAction(Request $request, $id)
    {
        $offer = $this->getOffer($id);
        $offer->setStarred(!$request->attributes->get('_unstar', false));

        return $this->saveAndReturn($offer, Response::HTTP_NO_CONTENT);
    }

    private function saveAndReturn(Offer $offer, $status)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->persist($offer);
        $em->flush();

        return new Response('', $status);
    }
}
