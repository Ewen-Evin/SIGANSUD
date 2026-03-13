<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/especes')]
class EspeceController extends AbstractController
{
    #[Route('', name: 'espece_index')]
    public function index(ApiService $api): Response
    {
        try {
            $especes = $api->getEspeces();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            $especes = [];
        }

        return $this->render('espece/index.html.twig', [
            'especes' => $especes,
        ]);
    }

    #[Route('/{id}/animaux', name: 'espece_animaux')]
    public function animaux(int $id, ApiService $api): Response
    {
        try {
            $especes = $api->getEspeces();
            $espece = null;
            foreach ($especes as $e) {
                if ($e['id'] == $id) {
                    $espece = $e;
                    break;
                }
            }
            $animaux = $api->getEspeceAnimaux($id);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            return $this->redirectToRoute('espece_index');
        }

        return $this->render('espece/animaux.html.twig', [
            'espece' => $espece,
            'animaux' => $animaux,
        ]);
    }

    #[Route('/{id}/menus', name: 'espece_menus')]
    public function menus(int $id, Request $request, ApiService $api): Response
    {
        try {
            $especes = $api->getEspeces();
            $espece = null;
            foreach ($especes as $e) {
                if ($e['id'] == $id) {
                    $espece = $e;
                    break;
                }
            }
            $menusRecommandes = $api->getEspeceMenus($id);
            $tousMenus = $api->getMenus();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            return $this->redirectToRoute('espece_index');
        }

        $idsRecommandes = array_column($menusRecommandes, 'id');
        $menusDisponibles = array_filter($tousMenus, function ($m) use ($idsRecommandes) {
            return !in_array($m['id'], $idsRecommandes);
        });

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');
            $menuId = (int) $request->request->get('menu_id');

            try {
                if ($action === 'recommander') {
                    $api->recommanderMenu($id, $menuId);
                    $this->addFlash('success', 'Menu recommande pour cette espece.');
                } elseif ($action === 'retirer') {
                    $api->retirerMenu($id, $menuId);
                    $this->addFlash('success', 'Menu retire.');
                }
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }

            return $this->redirectToRoute('espece_menus', ['id' => $id]);
        }

        return $this->render('espece/menus.html.twig', [
            'espece' => $espece,
            'menusRecommandes' => $menusRecommandes,
            'menusDisponibles' => $menusDisponibles,
        ]);
    }
}
