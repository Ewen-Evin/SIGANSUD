<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestionnaires')]
class GestionnaireController extends AbstractController
{
    #[Route('', name: 'gestionnaire_index')]
    public function index(ApiService $api): Response
    {
        try {
            $gestionnaires = $api->getGestionnaires();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            $gestionnaires = [];
        }

        return $this->render('gestionnaire/index.html.twig', [
            'gestionnaires' => $gestionnaires,
        ]);
    }

    #[Route('/nouveau', name: 'gestionnaire_new')]
    public function new(Request $request, ApiService $api): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $api->createGestionnaire([
                    'login' => $request->request->get('login'),
                    'mot_de_passe' => $request->request->get('mot_de_passe'),
                    'nom' => $request->request->get('nom'),
                    'prenom' => $request->request->get('prenom'),
                    'role' => $request->request->get('role', 'gestionnaire'),
                ]);
                $this->addFlash('success', 'Gestionnaire cree avec succes.');
                return $this->redirectToRoute('gestionnaire_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('gestionnaire/form.html.twig', [
            'gestionnaire' => null,
            'action' => 'Ajouter',
        ]);
    }

    #[Route('/{id}/modifier', name: 'gestionnaire_edit')]
    public function edit(int $id, Request $request, ApiService $api): Response
    {
        try {
            $gestionnaires = $api->getGestionnaires();
            $gestionnaire = null;
            foreach ($gestionnaires as $g) {
                if ($g['id'] == $id) {
                    $gestionnaire = $g;
                    break;
                }
            }
            if (!$gestionnaire) {
                throw new \Exception('Gestionnaire introuvable');
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Gestionnaire introuvable.');
            return $this->redirectToRoute('gestionnaire_index');
        }

        if ($request->isMethod('POST')) {
            try {
                $data = [
                    'nom' => $request->request->get('nom'),
                    'prenom' => $request->request->get('prenom'),
                    'role' => $request->request->get('role', 'gestionnaire'),
                ];
                $password = $request->request->get('mot_de_passe');
                if (!empty($password)) {
                    $data['mot_de_passe'] = $password;
                }
                $api->updateGestionnaire($id, $data);
                $this->addFlash('success', 'Gestionnaire modifie.');
                return $this->redirectToRoute('gestionnaire_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('gestionnaire/form.html.twig', [
            'gestionnaire' => $gestionnaire,
            'action' => 'Modifier',
        ]);
    }

    #[Route('/{id}/supprimer', name: 'gestionnaire_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, ApiService $api): Response
    {
        $currentUser = $request->getSession()->get('gestionnaire');
        if ($currentUser && $currentUser['id'] == $id) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre propre compte.');
            return $this->redirectToRoute('gestionnaire_index');
        }

        try {
            $api->deleteGestionnaire($id);
            $this->addFlash('success', 'Gestionnaire supprime.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
        }

        return $this->redirectToRoute('gestionnaire_index');
    }
}
