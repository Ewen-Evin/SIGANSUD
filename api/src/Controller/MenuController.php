<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Espece;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    #[Route('/api/menus', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $menus = $em->getRepository(Menu::class)->findAll();
        $data = array_map(fn(Menu $m) => [
            'id' => $m->getId(),
            'aliment' => $m->getAliment(),
            'quantite' => $m->getQteAliment(),
        ], $menus);

        return $this->json($data);
    }

    #[Route('/api/menus/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $menu = $em->getRepository(Menu::class)->find($id);
        if (!$menu) {
            return $this->json(['error' => 'Menu non trouve'], 404);
        }

        return $this->json([
            'id' => $menu->getId(),
            'aliment' => $menu->getAliment(),
            'quantite' => $menu->getQteAliment(),
        ]);
    }

    #[Route('/api/menus', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $menu = new Menu();
        $menu->setAliment($data['aliment']);
        $menu->setQteAliment($data['quantite']);

        $em->persist($menu);
        $em->flush();

        return $this->json(['id' => $menu->getId()], 201);
    }

    #[Route('/api/menus/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $menu = $em->getRepository(Menu::class)->find($id);
        if (!$menu) {
            return $this->json(['error' => 'Menu non trouve'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['aliment'])) $menu->setAliment($data['aliment']);
        if (isset($data['quantite'])) $menu->setQteAliment($data['quantite']);

        $em->flush();

        return $this->json(['message' => 'Menu modifie']);
    }

    #[Route('/api/menus/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $menu = $em->getRepository(Menu::class)->find($id);
        if (!$menu) {
            return $this->json(['error' => 'Menu non trouve'], 404);
        }

        $em->remove($menu);
        $em->flush();

        return $this->json(['message' => 'Menu supprime']);
    }

    #[Route('/api/especes/{idEspece}/menus', methods: ['POST'])]
    public function recommander(int $idEspece, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($idEspece);
        if (!$espece) {
            return $this->json(['error' => 'Espece non trouvee'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $menu = $em->getRepository(Menu::class)->find($data['id_menu']);
        if (!$menu) {
            return $this->json(['error' => 'Menu non trouve'], 404);
        }

        $menu->addEspece($espece);
        $em->flush();

        return $this->json(['message' => 'Menu recommande pour cette espece'], 201);
    }

    #[Route('/api/especes/{idEspece}/menus/{idMenu}', methods: ['DELETE'])]
    public function unrecommander(int $idEspece, int $idMenu, EntityManagerInterface $em): JsonResponse
    {
        $espece = $em->getRepository(Espece::class)->find($idEspece);
        $menu = $em->getRepository(Menu::class)->find($idMenu);

        if (!$espece || !$menu) {
            return $this->json(['error' => 'Espece ou menu non trouve'], 404);
        }

        $menu->removeEspece($espece);
        $em->flush();

        return $this->json(['message' => 'Recommandation retiree']);
    }
}
