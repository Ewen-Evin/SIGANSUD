<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/menus')]
class MenuController extends AbstractController
{
    #[Route('', name: 'menu_index')]
    public function index(ApiService $api): Response
    {
        try {
            $menus = $api->getMenus();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            $menus = [];
        }

        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
        ]);
    }

    #[Route('/nouveau', name: 'menu_new')]
    public function new(Request $request, ApiService $api): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $api->createMenu([
                    'aliment' => $request->request->get('aliment'),
                    'quantite' => (int) $request->request->get('quantite'),
                ]);
                $this->addFlash('success', 'Menu cree avec succes.');
                return $this->redirectToRoute('menu_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('menu/form.html.twig', [
            'menu' => null,
            'action' => 'Ajouter',
        ]);
    }

    #[Route('/{id}/modifier', name: 'menu_edit')]
    public function edit(int $id, Request $request, ApiService $api): Response
    {
        try {
            $menu = $api->getMenu($id);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Menu introuvable.');
            return $this->redirectToRoute('menu_index');
        }

        if ($request->isMethod('POST')) {
            try {
                $api->updateMenu($id, [
                    'aliment' => $request->request->get('aliment'),
                    'quantite' => (int) $request->request->get('quantite'),
                ]);
                $this->addFlash('success', 'Menu modifie avec succes.');
                return $this->redirectToRoute('menu_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('menu/form.html.twig', [
            'menu' => $menu,
            'action' => 'Modifier',
        ]);
    }

    #[Route('/{id}/supprimer', name: 'menu_delete', methods: ['POST'])]
    public function delete(int $id, ApiService $api): Response
    {
        try {
            $api->deleteMenu($id);
            $this->addFlash('success', 'Menu supprime.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
        }

        return $this->redirectToRoute('menu_index');
    }
}
