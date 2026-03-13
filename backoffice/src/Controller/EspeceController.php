<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/especes')]
class EspeceController extends AbstractController
{
    #[Route('', name: 'espece_index')]
    public function index(ApiService $api): Response
    {
        try {
            $especes = $api->getEspeces();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            $especes = [];
        }

        return $this->render('espece/index.html.twig', [
            'especes' => $especes,
        ]);
    }

    #[Route('/nouveau', name: 'espece_new')]
    public function new(Request $request, ApiService $api): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $api->createEspece([
                    'nom' => $request->request->get('nom'),
                ]);
                $this->addFlash('success', 'Espece creee avec succes.');
                return $this->redirectToRoute('espece_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('espece/form.html.twig', [
            'espece' => null,
            'action' => 'Ajouter',
        ]);
    }

    #[Route('/{id}/modifier', name: 'espece_edit', requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request, ApiService $api): Response
    {
        try {
            $espece = $this->findEspece($api, $id);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Espece introuvable.');
            return $this->redirectToRoute('espece_index');
        }

        if ($request->isMethod('POST')) {
            try {
                $api->updateEspece($id, [
                    'nom' => $request->request->get('nom'),
                ]);
                $this->addFlash('success', 'Espece modifiee.');
                return $this->redirectToRoute('espece_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('espece/form.html.twig', [
            'espece' => $espece,
            'action' => 'Modifier',
        ]);
    }

    #[Route('/{id}/supprimer', name: 'espece_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, ApiService $api): Response
    {
        try {
            $api->deleteEspece($id);
            $this->addFlash('success', 'Espece supprimee.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur : impossible de supprimer (des animaux y sont peut-etre rattaches).');
        }

        return $this->redirectToRoute('espece_index');
    }

    #[Route('/{id}/animaux', name: 'espece_animaux')]
    public function animaux(int $id, ApiService $api): Response
    {
        try {
            $espece = $this->findEspece($api, $id);
            $animaux = $api->getEspeceAnimaux($id);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            return $this->redirectToRoute('espece_index');
        }

        return $this->render('espece/animaux.html.twig', [
            'espece' => $espece,
            'animaux' => $animaux,
        ]);
    }

    #[Route('/{id}/animaux/nouveau', name: 'espece_animal_new')]
    public function newAnimal(int $id, Request $request, ApiService $api): Response
    {
        try {
            $espece = $this->findEspece($api, $id);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Espece introuvable.');
            return $this->redirectToRoute('espece_index');
        }

        if ($request->isMethod('POST')) {
            try {
                $api->createAnimal($id, [
                    'nomBapteme' => $request->request->get('nomBapteme'),
                    'genre' => $request->request->get('genre'),
                    'dateNaissance' => $request->request->get('dateNaissance') ?: null,
                ]);
                $this->addFlash('success', 'Animal ajoute avec succes.');
                return $this->redirectToRoute('espece_animaux', ['id' => $id]);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('espece/animal_form.html.twig', [
            'espece' => $espece,
            'animal' => null,
            'action' => 'Ajouter',
        ]);
    }

    #[Route('/{id}/animaux/{nomBapteme}/modifier', name: 'espece_animal_edit')]
    public function editAnimal(int $id, string $nomBapteme, Request $request, ApiService $api): Response
    {
        try {
            $espece = $this->findEspece($api, $id);
            $animaux = $api->getEspeceAnimaux($id);
            $animal = null;
            foreach ($animaux as $a) {
                if ($a['nomBapteme'] === $nomBapteme) {
                    $animal = $a;
                    break;
                }
            }
            if (!$animal) {
                throw new \Exception('Animal introuvable');
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Animal introuvable.');
            return $this->redirectToRoute('espece_animaux', ['id' => $id]);
        }

        if ($request->isMethod('POST')) {
            try {
                $api->updateAnimal($id, $nomBapteme, [
                    'genre' => $request->request->get('genre'),
                    'dateNaissance' => $request->request->get('dateNaissance') ?: null,
                    'dateDeces' => $request->request->get('dateDeces') ?: null,
                ]);
                $this->addFlash('success', 'Animal modifie avec succes.');
                return $this->redirectToRoute('espece_animaux', ['id' => $id]);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('espece/animal_form.html.twig', [
            'espece' => $espece,
            'animal' => $animal,
            'action' => 'Modifier',
        ]);
    }

    #[Route('/{id}/animaux/{nomBapteme}/supprimer', name: 'espece_animal_delete', methods: ['POST'])]
    public function deleteAnimal(int $id, string $nomBapteme, ApiService $api): Response
    {
        try {
            $api->deleteAnimal($id, $nomBapteme);
            $this->addFlash('success', 'Animal supprime.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
        }

        return $this->redirectToRoute('espece_animaux', ['id' => $id]);
    }

    private function findEspece(ApiService $api, int $id): array
    {
        $especes = $api->getEspeces();
        foreach ($especes as $e) {
            if ($e['id'] == $id) {
                return $e;
            }
        }
        throw new \Exception('Espece introuvable');
    }

    #[Route('/{id}/menus', name: 'espece_menus')]
    public function menus(int $id, Request $request, ApiService $api): Response
    {
        try {
            $espece = $this->findEspece($api, $id);
            $menusRecommandes = $api->getEspeceMenus($id);
            $tousMenus = $api->getMenus();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            return $this->redirectToRoute('espece_index');
        }

        $idsRecommandes = array_column($menusRecommandes, 'id');
        $menusDisponibles = array_filter($tousMenus, function ($m) use ($idsRecommandes) {
            return !in_array($m['id'], $idsRecommandes);
        });

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');
            $menuId = (int) $request->request->get('menu_id');

            try {
                if ($action === 'recommander') {
                    $api->recommanderMenu($id, $menuId);
                    $this->addFlash('success', 'Menu recommande pour cette espece.');
                } elseif ($action === 'retirer') {
                    $api->retirerMenu($id, $menuId);
                    $this->addFlash('success', 'Menu retire.');
                }
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }

            return $this->redirectToRoute('espece_menus', ['id' => $id]);
        }

        return $this->render('espece/menus.html.twig', [
            'espece' => $espece,
            'menusRecommandes' => $menusRecommandes,
            'menusDisponibles' => $menusDisponibles,
        ]);
    }
}
