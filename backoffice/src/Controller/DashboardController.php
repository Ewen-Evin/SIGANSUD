<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(ApiService $api): Response
    {
        try {
            $soignants = $api->getSoignants();
            $especes = $api->getEspeces();
            $menus = $api->getMenus();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Impossible de contacter l\'API. Verifiez que le serveur API est lance.');
            $soignants = [];
            $especes = [];
            $menus = [];
        }

        return $this->render('dashboard/index.html.twig', [
            'nbSoignants' => count($soignants),
            'nbEspeces' => count($especes),
            'nbMenus' => count($menus),
        ]);
    }
}
