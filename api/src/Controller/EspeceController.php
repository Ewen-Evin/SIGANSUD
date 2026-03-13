<?php

namespace App\Controller;

use App\Entity\Espece;
use App\Entity\Animal;
use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/{id}', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($id);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        return $this->json([
            'id' => $espece->getId(),
            'nom' => $espece->getNom(),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $espece = new Espece();
        $espece->setNom($data['nom'] ?? '');

        $em->persist($espece);
        $em->flush();

        return $this->json([
            'id' => $espece->getId(),
            'nom' => $espece->getNom(),
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($id);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['nom'])) $espece->setNom($data['nom']);

        $em->flush();

        return $this->json([
            'id' => $espece->getId(),
            'nom' => $espece->getNom(),
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($id);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $em->remove($espece);
        $em->flush();

        return $this->json(['message' => 'Espece supprimee']);
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

    // Ajouter un animal a une espece
    #[Route('/{idEspece}/animaux', methods: ['POST'])]
    public function createAnimal(int $idEspece, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($idEspece);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $nomBapteme = $data['nomBapteme'] ?? '';

        // Verifier si l'animal existe deja
        $existing = $em->getRepository(Animal::class)->findOneBy([
            'espece' => $espece,
            'nomBapteme' => $nomBapteme,
        ]);
        if ($existing) {
            return $this->json(['error' => 'Un animal avec ce nom existe deja pour cette espece'], 400);
        }

        $animal = new Animal();
        $animal->setEspece($espece);
        $animal->setNomBapteme($nomBapteme);
        $animal->setGenre($data['genre'] ?? 'M');
        if (!empty($data['dateNaissance'])) {
            $animal->setDateNaissance(new \DateTime($data['dateNaissance']));
        }
        if (!empty($data['dateDeces'])) {
            $animal->setDateDeces(new \DateTime($data['dateDeces']));
        }

        $em->persist($animal);
        $em->flush();

        return $this->json([
            'nomBapteme' => $animal->getNomBapteme(),
            'genre' => $animal->getGenre(),
            'dateNaissance' => $animal->getDateNaissance()?->format('Y-m-d'),
            'dateDeces' => $animal->getDateDeces()?->format('Y-m-d'),
        ], 201);
    }

    // Modifier un animal
    #[Route('/{idEspece}/animaux/{nomBapteme}', methods: ['PUT'])]
    public function updateAnimal(int $idEspece, string $nomBapteme, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($idEspece);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $animal = $em->getRepository(Animal::class)->findOneBy([
            'espece' => $espece,
            'nomBapteme' => $nomBapteme,
        ]);
        if (!$animal) {
            return $this->json(['error' => 'Animal non trouve'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['genre'])) $animal->setGenre($data['genre']);
        if (array_key_exists('dateNaissance', $data)) {
            $animal->setDateNaissance(!empty($data['dateNaissance']) ? new \DateTime($data['dateNaissance']) : null);
        }
        if (array_key_exists('dateDeces', $data)) {
            $animal->setDateDeces(!empty($data['dateDeces']) ? new \DateTime($data['dateDeces']) : null);
        }

        $em->flush();

        return $this->json([
            'nomBapteme' => $animal->getNomBapteme(),
            'genre' => $animal->getGenre(),
            'dateNaissance' => $animal->getDateNaissance()?->format('Y-m-d'),
            'dateDeces' => $animal->getDateDeces()?->format('Y-m-d'),
        ]);
    }

    // Supprimer un animal
    #[Route('/{idEspece}/animaux/{nomBapteme}', methods: ['DELETE'])]
    public function deleteAnimal(int $idEspece, string $nomBapteme, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($idEspece);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $animal = $em->getRepository(Animal::class)->findOneBy([
            'espece' => $espece,
            'nomBapteme' => $nomBapteme,
        ]);
        if (!$animal) {
            return $this->json(['error' => 'Animal non trouve'], 404);
        }

        $em->remove($animal);
        $em->flush();

        return $this->json(['message' => 'Animal supprime']);
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
