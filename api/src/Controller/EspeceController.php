<?php

namespace App\Controller;

use App\Entity\Espece;
use App\Entity\Animal;
use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/especes')]
class EspeceController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $especes = $em->getRepository(Espece::class)->findAll();
        $data = array_map(fn(Espece $e) => [
            'id' => $e->getId(),
            'nom' => $e->getNom(),
        ], $especes);

        return $this->json($data);
    }

    #[Route('/{idEspece}/animaux', methods: ['GET'])]
    public function animaux(int $idEspece, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($idEspece);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $data = array_map(fn(Animal $a) => [
            'nomBapteme' => $a->getNomBapteme(),
            'dateNaissance' => $a->getDateNaissance()?->format('Y-m-d'),
            'dateDeces' => $a->getDateDeces()?->format('Y-m-d'),
            'genre' => $a->getGenre(),
        ], $espece->getAnimaux()->toArray());

        return $this->json($data);
    }

    #[Route('/{idEspece}/menus', methods: ['GET'])]
    public function menus(int $idEspece, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($idEspece);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $data = array_map(fn(Menu $m) => [
            'id' => $m->getId(),
            'aliment' => $m->getAliment(),
            'quantite' => $m->getQteAliment(),
        ], $espece->getMenus()->toArray());

        return $this->json($data);
    }
}
