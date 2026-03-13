<?php

namespace App\Controller;

use App\Entity\Gestionnaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class GestionnaireController extends AbstractController
{
    // Login gestionnaire
    #[Route('/api/gestionnaires/login', methods: ['POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $login = $data['login'] ?? '';
        $password = $data['mot_de_passe'] ?? '';

        $gestionnaire = $em->getRepository(Gestionnaire::class)->findOneBy(['login' => $login]);

        if (!$gestionnaire || !$hasher->isPasswordValid($gestionnaire, $password)) {
            return $this->json(['error' => 'Identifiants invalides'], 401);
        }

        $token = base64_encode('gest:' . $gestionnaire->getId() . ':' . bin2hex(random_bytes(16)));

        return $this->json([
            'token' => $token,
            'id' => $gestionnaire->getId(),
            'login' => $gestionnaire->getLogin(),
            'nom' => $gestionnaire->getNom(),
            'prenom' => $gestionnaire->getPrenom(),
            'role' => $gestionnaire->getRole(),
        ]);
    }

    // Liste des gestionnaires
    #[Route('/api/gestionnaires', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $gestionnaires = $em->getRepository(Gestionnaire::class)->findAll();
        $data = [];
        foreach ($gestionnaires as $g) {
            $data[] = [
                'id' => $g->getId(),
                'login' => $g->getLogin(),
                'nom' => $g->getNom(),
                'prenom' => $g->getPrenom(),
                'role' => $g->getRole(),
            ];
        }
        return $this->json($data);
    }

    // Creer un gestionnaire
    #[Route('/api/gestionnaires', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $existing = $em->getRepository(Gestionnaire::class)->findOneBy(['login' => $data['login'] ?? '']);
        if ($existing) {
            return $this->json(['error' => 'Ce login existe deja'], 400);
        }

        $gestionnaire = new Gestionnaire();
        $gestionnaire->setLogin($data['login'] ?? '');
        $gestionnaire->setNom($data['nom'] ?? '');
        $gestionnaire->setPrenom($data['prenom'] ?? '');
        $gestionnaire->setRole($data['role'] ?? 'gestionnaire');
        $gestionnaire->setPassword($hasher->hashPassword($gestionnaire, $data['mot_de_passe'] ?? ''));

        $em->persist($gestionnaire);
        $em->flush();

        return $this->json([
            'id' => $gestionnaire->getId(),
            'login' => $gestionnaire->getLogin(),
            'nom' => $gestionnaire->getNom(),
            'prenom' => $gestionnaire->getPrenom(),
            'role' => $gestionnaire->getRole(),
        ], 201);
    }

    // Modifier un gestionnaire
    #[Route('/api/gestionnaires/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $gestionnaire = $em->getRepository(Gestionnaire::class)->find($id);
        if (!$gestionnaire) {
            return $this->json(['error' => 'Gestionnaire introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom'])) $gestionnaire->setNom($data['nom']);
        if (isset($data['prenom'])) $gestionnaire->setPrenom($data['prenom']);
        if (isset($data['role'])) $gestionnaire->setRole($data['role']);
        if (!empty($data['mot_de_passe'])) {
            $gestionnaire->setPassword($hasher->hashPassword($gestionnaire, $data['mot_de_passe']));
        }

        $em->flush();

        return $this->json([
            'id' => $gestionnaire->getId(),
            'login' => $gestionnaire->getLogin(),
            'nom' => $gestionnaire->getNom(),
            'prenom' => $gestionnaire->getPrenom(),
            'role' => $gestionnaire->getRole(),
        ]);
    }

    // Supprimer un gestionnaire
    #[Route('/api/gestionnaires/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $gestionnaire = $em->getRepository(Gestionnaire::class)->find($id);
        if (!$gestionnaire) {
            return $this->json(['error' => 'Gestionnaire introuvable'], 404);
        }

        $em->remove($gestionnaire);
        $em->flush();

        return $this->json(['message' => 'Gestionnaire supprime']);
    }
}
