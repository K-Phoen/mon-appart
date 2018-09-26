<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\EditConfigType;
use App\Repository\ConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /** @var ConfigRepository */
    private $configRepo;

    public function __construct(ConfigRepository $configRepo)
    {
        $this->configRepo = $configRepo;
    }

    /**
     * @Route("/config", name="edit_config")
     */
    public function listOffers(Request $request)
    {
        $config = $this->configRepo->mainConfig();
        $form = $this->createForm(EditConfigType::class, $config);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->configRepo->persist($config);

            $this->addFlash('success', 'Configuration mise à jour.');

            return $this->redirectToRoute('edit_config');
        }

        return $this->render('config/edit.html.twig', [
            'configForm' => $form->createView(),
        ]);
    }
}
