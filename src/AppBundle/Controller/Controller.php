<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected function getOffer($id)
    {
        $offer = $this->get('repository.offer')->find($id);

        if ($offer === null) {
            throw $this->createNotFoundException('Offer not found.');
        }

        return $offer;
    }
}
