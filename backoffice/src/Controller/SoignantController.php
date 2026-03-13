<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/soignants')]
class SoignantController extends AbstractController
{
    #[Route('', name: 'soignant_index')]
    public function index(ApiService $api): Response
    {
        try {
            $soignants = $api->getSoignants();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            $soignants = [];
        }

        return $this->render('soignant/index.html.twig', [
            'soignants' => $soignants,
        ]);
    }

    #[Route('/nouveau', name: 'soignant_new')]
    public function new(Request $request, ApiService $api): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $api->createSoignant([
                    'matricule' => $request->request->get('matricule'),
                    'nom' => $request->request->get('nom'),
                    'prenom' => $request->request->get('prenom'),
                    'tel' => $request->request->get('tel'),
                    'adresse' => $request->request->get('adresse'),
                    'mot_de_passe' => $request->request->get('mot_de_passe'),
                ]);
                $this->addFlash('success', 'Soignant cree avec succes.');
                return $this->redirectToRoute('soignant_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('soignant/form.html.twig', [
            'soignant' => null,
            'action' => 'Ajouter',
        ]);
    }

    #[Route('/{matricule}/modifier', name: 'soignant_edit')]
    public function edit(string $matricule, Request $request, ApiService $api): Response
    {
        try {
            $soignant = $api->getSoignant($matricule);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Soignant introuvable.');
            return $this->redirectToRoute('soignant_index');
        }

        if ($request->isMethod('POST')) {
            try {
                $data = [
                    'nom' => $request->request->get('nom'),
                    'prenom' => $request->request->get('prenom'),
                    'tel' => $request->request->get('tel'),
                    'adresse' => $request->request->get('adresse'),
                ];
                $password = $request->request->get('mot_de_passe');
                if (!empty($password)) {
                    $data['mot_de_passe'] = $password;
                }
                $api->updateSoignant($matricule, $data);
                $this->addFlash('success', 'Soignant modifie avec succes.');
                return $this->redirectToRoute('soignant_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }
        }

        return $this->render('soignant/form.html.twig', [
            'soignant' => $soignant,
            'action' => 'Modifier',
        ]);
    }

    #[Route('/{matricule}/supprimer', name: 'soignant_delete', methods: ['POST'])]
    public function delete(string $matricule, ApiService $api): Response
    {
        try {
            $api->deleteSoignant($matricule);
            $this->addFlash('success', 'Soignant supprime.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
        }

        return $this->redirectToRoute('soignant_index');
    }

    #[Route('/{matricule}/especes', name: 'soignant_especes')]
    public function especes(string $matricule, Request $request, ApiService $api): Response
    {
        try {
            $soignant = $api->getSoignant($matricule);
            $especesAffectees = $api->getSoignantEspeces($matricule);
            $toutesEspeces = $api->getEspeces();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur API : ' . $e->getMessage());
            return $this->redirectToRoute('soignant_index');
        }

        // Filtrer les especes non affectees
        $idsAffectees = array_column($especesAffectees, 'id');
        $especesDisponibles = array_filter($toutesEspeces, function ($e) use ($idsAffectees) {
            return !in_array($e['id'], $idsAffectees);
        });

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');
            $especeId = (int) $request->request->get('espece_id');

            try {
                if ($action === 'affecter') {
                    if (count($especesAffectees) >= 3) {
                        $this->addFlash('warning', 'Maximum 3 especes par soignant.');
                    } else {
                        $api->affecterEspece($matricule, $especeId);
                        $this->addFlash('success', 'Espece affectee.');
                    }
                } elseif ($action === 'retirer') {
                    $api->retirerEspece($matricule, $especeId);
                    $this->addFlash('success', 'Espece retiree.');
                }
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur : ' . $e->getMessage());
            }

            return $this->redirectToRoute('soignant_especes', ['matricule' => $matricule]);
        }

        return $this->render('soignant/especes.html.twig', [
            'soignant' => $soignant,
            'especesAffectees' => $especesAffectees,
            'especesDisponibles' => $especesDisponibles,
        ]);
    }
}
