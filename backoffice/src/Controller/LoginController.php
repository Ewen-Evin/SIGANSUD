<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request, ApiService $api): Response
    {
        // Si deja connecte, rediriger
        if ($request->getSession()->get('gestionnaire')) {
            return $this->redirectToRoute('dashboard');
        }

        $error = null;

        if ($request->isMethod('POST')) {
            $login = $request->request->get('login', '');
            $password = $request->request->get('mot_de_passe', '');

            try {
                $result = $api->loginGestionnaire($login, $password);
                // Stocker en session
                $request->getSession()->set('gestionnaire', $result);
                $this->addFlash('success', 'Bienvenue ' . $result['prenom'] . ' !');
                return $this->redirectToRoute('dashboard');
            } catch (\Exception $e) {
                $error = 'Identifiants invalides.';
            }
        }

        return $this->render('login.html.twig', [
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $request->getSession()->invalidate();
        return $this->redirectToRoute('login');
    }
}
