<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function index(Request $request, ApiService $api): Response
    {
        $gestionnaire = $request->getSession()->get('gestionnaire');

        if ($request->isMethod('POST')) {
            try {
                $data = [
                    'nom' => $request->request->get('nom'),
                    'prenom' => $request->request->get('prenom'),
                ];
                $password = $request->request->get('mot_de_passe');
                if (!empty($password)) {
                    $data['mot_de_passe'] = $password;
                }

                $api->updateGestionnaire($gestionnaire['id'], $data);

                // Mettre a jour la session
                $gestionnaire['nom'] = $data['nom'];
                $gestionnaire['prenom'] = $data['prenom'];
                $request->getSession()->set('gestionnaire', $gestionnaire);

                $this->addFlash('success', 'Profil mis a jour.');
                return $this->redirectToRoute('profil');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('profil.html.twig', [
            'gestionnaire' => $gestionnaire,
        ]);
    }
}
