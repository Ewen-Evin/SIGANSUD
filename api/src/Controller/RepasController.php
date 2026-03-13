<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Entity\DateRepas;
use App\Entity\Espece;
use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RepasController extends AbstractController
{
    #[Route('/api/repas', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $espece = $em->getRepository(Espece::class)->find($data['id_espece']);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $menu = $em->getRepository(Menu::class)->find($data['id_menu']);
        if (!$menu) {
            return $this->json(['error' => 'Menu non trouve'], 404);
        }

        // Creer une nouvelle date de repas
        $dateRepas = new DateRepas();
        $em->persist($dateRepas);
        $em->flush();

        $repas = new Repas();
        $repas->setDateRepas($dateRepas);
        $repas->setEspece($espece);
        $repas->setNomBaptemeAnimal($data['nomBapteme']);
        $repas->setQuantite($data['quantite']);
        $repas->setMenu($menu);

        $em->persist($repas);
        $em->flush();

        return $this->json(['message' => 'Repas enregistre', 'id_date_repas' => $dateRepas->getId()], 201);
    }

    #[Route('/api/especes/{idEspece}/animaux/{nomBapteme}/repas', methods: ['GET'])]
    public function byAnimal(int $idEspece, string $nomBapteme, EntityManagerInterface $em): JsonResponse
    {
        $repas = $em->getRepository(Repas::class)->findBy([
            'espece' => $idEspece,
            'nomBaptemeAnimal' => $nomBapteme,
        ]);

        $data = array_map(fn(Repas $r) => [
            'id_date_repas' => $r->getDateRepas()->getId(),
            'aliment' => $r->getMenu()->getAliment(),
            'quantite' => $r->getQuantite(),
        ], $repas);

        return $this->json($data);
    }
}
