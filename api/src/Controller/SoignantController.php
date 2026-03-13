<?php

namespace App\Controller;

use App\Entity\Soignant;
use App\Entity\Espece;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/soignants')]
class SoignantController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $soignants = $em->getRepository(Soignant::class)->findAll();
        $data = array_map(fn(Soignant $s) => [
            'matricule' => $s->getMatricule(),
            'nom' => $s->getNom(),
            'prenom' => $s->getPrenom(),
            'tel' => $s->getTel(),
            'adresse' => $s->getAdresse(),
        ], $soignants);

        return $this->json($data);
    }

    #[Route('/{matricule}', methods: ['GET'])]
    public function show(string $matricule, EntityManagerInterface $em): JsonResponse
    {
        $soignant = $em->getRepository(Soignant::class)->find($matricule);
        if (!$soignant) {
            return $this->json(['error' => 'Soignant non trouve'], 404);
        }

        return $this->json([
            'matricule' => $soignant->getMatricule(),
            'nom' => $soignant->getNom(),
            'prenom' => $soignant->getPrenom(),
            'tel' => $soignant->getTel(),
            'adresse' => $soignant->getAdresse(),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $soignant = new Soignant();
        $soignant->setMatricule($data['matricule']);
        $soignant->setNom($data['nom']);
        $soignant->setPrenom($data['prenom']);
        $soignant->setTel($data['tel'] ?? null);
        $soignant->setAdresse($data['adresse'] ?? null);
        $soignant->setPassword($hasher->hashPassword($soignant, $data['mot_de_passe']));

        $em->persist($soignant);
        $em->flush();

        return $this->json(['matricule' => $soignant->getMatricule()], 201);
    }

    #[Route('/{matricule}', methods: ['PUT'])]
    public function update(string $matricule, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $soignant = $em->getRepository(Soignant::class)->find($matricule);
        if (!$soignant) {
            return $this->json(['error' => 'Soignant non trouve'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['nom'])) $soignant->setNom($data['nom']);
        if (isset($data['prenom'])) $soignant->setPrenom($data['prenom']);
        if (isset($data['tel'])) $soignant->setTel($data['tel']);
        if (isset($data['adresse'])) $soignant->setAdresse($data['adresse']);

        $em->flush();

        return $this->json(['message' => 'Soignant modifie']);
    }

    #[Route('/{matricule}', methods: ['DELETE'])]
    public function delete(string $matricule, EntityManagerInterface $em): JsonResponse
    {
        $soignant = $em->getRepository(Soignant::class)->find($matricule);
        if (!$soignant) {
            return $this->json(['error' => 'Soignant non trouve'], 404);
        }

        $em->remove($soignant);
        $em->flush();

        return $this->json(['message' => 'Soignant supprime']);
    }

    #[Route('/{matricule}/especes', methods: ['GET'])]
    public function especes(string $matricule, EntityManagerInterface $em): JsonResponse
    {
        $soignant = $em->getRepository(Soignant::class)->find($matricule);
        if (!$soignant) {
            return $this->json(['error' => 'Soignant non trouve'], 404);
        }

        $data = array_map(fn(Espece $e) => [
            'id' => $e->getId(),
            'nom' => $e->getNom(),
        ], $soignant->getEspeces()->toArray());

        return $this->json($data);
    }

    #[Route('/{matricule}/especes', methods: ['POST'])]
    public function addEspece(string $matricule, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $soignant = $em->getRepository(Soignant::class)->find($matricule);
        if (!$soignant) {
            return $this->json(['error' => 'Soignant non trouve'], 404);
        }

        if ($soignant->getEspeces()->count() >= 3) {
            return $this->json(['error' => 'Maximum 3 especes par soignant'], 400);
        }

        $data = json_decode($request->getContent(), true);
        $espece = $em->getRepository(Espece::class)->find($data['id_espece']);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $soignant->addEspece($espece);
        $em->flush();

        return $this->json(['message' => 'Espece affectee'], 201);
    }

    #[Route('/{matricule}/especes/{idEspece}', methods: ['DELETE'])]
    public function removeEspece(string $matricule, int $idEspece, EntityManagerInterface $em): JsonResponse
    {
        $soignant = $em->getRepository(Soignant::class)->find($matricule);
        if (!$soignant) {
            return $this->json(['error' => 'Soignant non trouve'], 404);
        }

        $espece = $em->getRepository(Espece::class)->find($idEspece);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $soignant->removeEspece($espece);
        $em->flush();

        return $this->json(['message' => 'Espece retiree']);
    }
}
