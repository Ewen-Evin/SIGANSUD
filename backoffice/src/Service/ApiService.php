<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private string $baseUrl;

    public function __construct(
        private HttpClientInterface $httpClient,
        string $apiBaseUrl = 'http://localhost:8000'
    ) {
        $this->baseUrl = rtrim($apiBaseUrl, '/');
    }

    // ========================
    // SOIGNANTS
    // ========================

    public function getSoignants(): array
    {
        return $this->get('/api/soignants');
    }

    public function getSoignant(string $matricule): array
    {
        return $this->get('/api/soignants/' . $matricule);
    }

    public function createSoignant(array $data): array
    {
        return $this->post('/api/soignants', $data);
    }

    public function updateSoignant(string $matricule, array $data): array
    {
        return $this->put('/api/soignants/' . $matricule, $data);
    }

    public function deleteSoignant(string $matricule): void
    {
        $this->delete('/api/soignants/' . $matricule);
    }

    // Specialisations soignant <-> espece
    public function getSoignantEspeces(string $matricule): array
    {
        return $this->get('/api/soignants/' . $matricule . '/especes');
    }

    public function affecterEspece(string $matricule, int $especeId): array
    {
        return $this->post('/api/soignants/' . $matricule . '/especes', [
            'id_espece' => $especeId,
        ]);
    }

    public function retirerEspece(string $matricule, int $especeId): void
    {
        $this->delete('/api/soignants/' . $matricule . '/especes/' . $especeId);
    }

    // ========================
    // ESPECES
    // ========================

    public function getEspeces(): array
    {
        return $this->get('/api/especes');
    }

    public function getEspeceAnimaux(int $especeId): array
    {
        return $this->get('/api/especes/' . $especeId . '/animaux');
    }

    public function getEspeceMenus(int $especeId): array
    {
        return $this->get('/api/especes/' . $especeId . '/menus');
    }

    public function recommanderMenu(int $especeId, int $menuId): array
    {
        return $this->post('/api/especes/' . $especeId . '/menus', [
            'id_menu' => $menuId,
        ]);
    }

    public function retirerMenu(int $especeId, int $menuId): void
    {
        $this->delete('/api/especes/' . $especeId . '/menus/' . $menuId);
    }

    // ========================
    // MENUS
    // ========================

    public function getMenus(): array
    {
        return $this->get('/api/menus');
    }

    public function getMenu(int $id): array
    {
        return $this->get('/api/menus/' . $id);
    }

    public function createMenu(array $data): array
    {
        return $this->post('/api/menus', $data);
    }

    public function updateMenu(int $id, array $data): array
    {
        return $this->put('/api/menus/' . $id, $data);
    }

    public function deleteMenu(int $id): void
    {
        $this->delete('/api/menus/' . $id);
    }

    // ========================
    // GESTIONNAIRES
    // ========================

    public function loginGestionnaire(string $login, string $password): array
    {
        return $this->post('/api/gestionnaires/login', [
            'login' => $login,
            'mot_de_passe' => $password,
        ]);
    }

    public function getGestionnaires(): array
    {
        return $this->get('/api/gestionnaires');
    }

    public function createGestionnaire(array $data): array
    {
        return $this->post('/api/gestionnaires', $data);
    }

    public function updateGestionnaire(int $id, array $data): array
    {
        return $this->put('/api/gestionnaires/' . $id, $data);
    }

    public function deleteGestionnaire(int $id): void
    {
        $this->delete('/api/gestionnaires/' . $id);
    }

    // ========================
    // HTTP helpers
    // ========================

    private function get(string $uri): array
    {
        $response = $this->httpClient->request('GET', $this->baseUrl . $uri);
        return $response->toArray();
    }

    private function post(string $uri, array $data): array
    {
        $response = $this->httpClient->request('POST', $this->baseUrl . $uri, [
            'json' => $data,
        ]);
        return $response->toArray();
    }

    private function put(string $uri, array $data): array
    {
        $response = $this->httpClient->request('PUT', $this->baseUrl . $uri, [
            'json' => $data,
        ]);
        return $response->toArray();
    }

    private function delete(string $uri): void
    {
        $this->httpClient->request('DELETE', $this->baseUrl . $uri);
    }
}
