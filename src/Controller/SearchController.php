<?php

declare(strict_types=1);

namespace App\Controller;

use App\Search\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="show_search")
     */
    public function showSearch()
    {
        return $this->render('search/description.html.twig', [
            'criteria' => Request::criteria(),
        ]);
    }
}
