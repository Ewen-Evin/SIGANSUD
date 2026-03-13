<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Soignant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', methods: ['POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $matricule = $data['matricule'] ?? '';
        $password = $data['mot_de_passe'] ?? '';

        $soignant = $em->getRepository(Soignant::class)->find($matricule);

        if (!$soignant || !$hasher->isPasswordValid($soignant, $password)) {
            return $this->json(['error' => 'Identifiants invalides'], 401);
        }

        // Token simple base64 (pour le projet scolaire)
        $token = base64_encode($soignant->getMatricule() . ':' . bin2hex(random_bytes(16)));

        return $this->json([
            'token' => $token,
            'matricule' => $soignant->getMatricule(),
            'nom' => $soignant->getNom(),
            'prenom' => $soignant->getPrenom(),
        ]);
    }
}
