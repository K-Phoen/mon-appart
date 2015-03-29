<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $offers = $this->get('repository.offer')->findAll();

        return $this->render('AppBundle:Default:index.html.twig', [
            'offers' => $offers,
        ]);
    }
}
