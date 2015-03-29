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
            'createdAt' => 'DESC',
        ]);

        return $this->render('AppBundle:Default:index.html.twig', [
            'offers' => $offers,
        ]);
    }

    public function flagAsViewAction(Request $request)
    {
        $offer = $this->get('repository.offer')->find($request->query->get('id'));
        $offer->setViewed(true);

        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->persist($offer);
        $em->flush();

        return $this->redirectToRoute('home');
    }
}
