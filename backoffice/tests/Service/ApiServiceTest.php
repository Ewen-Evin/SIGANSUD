<?php

namespace App\Tests\Service;

use App\Service\ApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiServiceTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private ApiService $apiService;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->apiService = new ApiService($this->httpClient, 'http://localhost:8000');
    }

    private function mockResponse(array $data, string $method = 'GET', string $expectedUri = ''): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn($data);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $method,
                $this->callback(fn(string $url) => $expectedUri === '' || str_contains($url, $expectedUri))
            )
            ->willReturn($response);
    }

    // ========================
    // SOIGNANTS
    // ========================

    public function testGetSoignants(): void
    {
        $expected = [
            ['matricule' => 'SOI001', 'nom' => 'Dupont', 'prenom' => 'Marie'],
        ];
        $this->mockResponse($expected, 'GET', '/api/soignants');

        $result = $this->apiService->getSoignants();
        $this->assertSame($expected, $result);
    }

    public function testGetSoignant(): void
    {
        $expected = ['matricule' => 'SOI001', 'nom' => 'Dupont', 'prenom' => 'Marie'];
        $this->mockResponse($expected, 'GET', '/api/soignants/SOI001');

        $result = $this->apiService->getSoignant('SOI001');
        $this->assertSame($expected, $result);
    }

    public function testCreateSoignant(): void
    {
        $data = ['matricule' => 'SOI004', 'nom' => 'Test', 'prenom' => 'User'];
        $expected = ['matricule' => 'SOI004'];
        $this->mockResponse($expected, 'POST', '/api/soignants');

        $result = $this->apiService->createSoignant($data);
        $this->assertSame($expected, $result);
    }

    public function testUpdateSoignant(): void
    {
        $data = ['nom' => 'NouveauNom'];
        $expected = ['message' => 'Soignant modifie'];
        $this->mockResponse($expected, 'PUT', '/api/soignants/SOI001');

        $result = $this->apiService->updateSoignant('SOI001', $data);
        $this->assertSame($expected, $result);
    }

    public function testDeleteSoignant(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', $this->stringContains('/api/soignants/SOI001'))
            ->willReturn($response);

        $this->apiService->deleteSoignant('SOI001');
        $this->assertTrue(true);
    }

    // ========================
    // SPECIALISATIONS
    // ========================

    public function testGetSoignantEspeces(): void
    {
        $expected = [['id' => 1, 'nom' => 'Lion']];
        $this->mockResponse($expected, 'GET', '/api/soignants/SOI001/especes');

        $result = $this->apiService->getSoignantEspeces('SOI001');
        $this->assertSame($expected, $result);
    }

    public function testAffecterEspece(): void
    {
        $expected = ['message' => 'Espece affectee'];
        $this->mockResponse($expected, 'POST', '/api/soignants/SOI001/especes');

        $result = $this->apiService->affecterEspece('SOI001', 1);
        $this->assertSame($expected, $result);
    }

    public function testRetirerEspece(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', $this->stringContains('/api/soignants/SOI001/especes/1'))
            ->willReturn($response);

        $this->apiService->retirerEspece('SOI001', 1);
        $this->assertTrue(true);
    }

    // ========================
    // ESPECES
    // ========================

    public function testGetEspeces(): void
    {
        $expected = [['id' => 1, 'nom' => 'Lion'], ['id' => 2, 'nom' => 'Tigre']];
        $this->mockResponse($expected, 'GET', '/api/especes');

        $result = $this->apiService->getEspeces();
        $this->assertSame($expected, $result);
    }

    public function testCreateEspece(): void
    {
        $expected = ['id' => 7, 'nom' => 'Panda'];
        $this->mockResponse($expected, 'POST', '/api/especes');

        $result = $this->apiService->createEspece(['nom' => 'Panda']);
        $this->assertSame($expected, $result);
    }

    public function testUpdateEspece(): void
    {
        $expected = ['id' => 1, 'nom' => 'Lion modifie'];
        $this->mockResponse($expected, 'PUT', '/api/especes/1');

        $result = $this->apiService->updateEspece(1, ['nom' => 'Lion modifie']);
        $this->assertSame($expected, $result);
    }

    public function testDeleteEspece(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', $this->stringContains('/api/especes/1'))
            ->willReturn($response);

        $this->apiService->deleteEspece(1);
        $this->assertTrue(true);
    }

    public function testGetEspeceAnimaux(): void
    {
        $expected = [['nomBapteme' => 'Simba', 'genre' => 'Male']];
        $this->mockResponse($expected, 'GET', '/api/especes/1/animaux');

        $result = $this->apiService->getEspeceAnimaux(1);
        $this->assertSame($expected, $result);
    }

    public function testGetEspeceMenus(): void
    {
        $expected = [['id' => 1, 'aliment' => 'Viande', 'quantite' => 500]];
        $this->mockResponse($expected, 'GET', '/api/especes/1/menus');

        $result = $this->apiService->getEspeceMenus(1);
        $this->assertSame($expected, $result);
    }

    // ========================
    // ANIMAUX
    // ========================

    public function testCreateAnimal(): void
    {
        $data = ['nomBapteme' => 'Nala', 'genre' => 'Femelle'];
        $expected = ['nomBapteme' => 'Nala', 'genre' => 'Femelle'];
        $this->mockResponse($expected, 'POST', '/api/especes/1/animaux');

        $result = $this->apiService->createAnimal(1, $data);
        $this->assertSame($expected, $result);
    }

    public function testUpdateAnimal(): void
    {
        $expected = ['nomBapteme' => 'Simba', 'genre' => 'Male'];
        $this->mockResponse($expected, 'PUT', '/api/especes/1/animaux/Simba');

        $result = $this->apiService->updateAnimal(1, 'Simba', ['genre' => 'Male']);
        $this->assertSame($expected, $result);
    }

    public function testDeleteAnimal(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', $this->stringContains('/api/especes/1/animaux/Simba'))
            ->willReturn($response);

        $this->apiService->deleteAnimal(1, 'Simba');
        $this->assertTrue(true);
    }

    // ========================
    // MENUS
    // ========================

    public function testGetMenus(): void
    {
        $expected = [['id' => 1, 'aliment' => 'Viande', 'quantite' => 500]];
        $this->mockResponse($expected, 'GET', '/api/menus');

        $result = $this->apiService->getMenus();
        $this->assertSame($expected, $result);
    }

    public function testGetMenu(): void
    {
        $expected = ['id' => 1, 'aliment' => 'Viande', 'quantite' => 500];
        $this->mockResponse($expected, 'GET', '/api/menus/1');

        $result = $this->apiService->getMenu(1);
        $this->assertSame($expected, $result);
    }

    public function testCreateMenu(): void
    {
        $expected = ['id' => 6];
        $this->mockResponse($expected, 'POST', '/api/menus');

        $result = $this->apiService->createMenu(['aliment' => 'Fruits', 'quantite' => 200]);
        $this->assertSame($expected, $result);
    }

    public function testUpdateMenu(): void
    {
        $expected = ['message' => 'Menu modifie'];
        $this->mockResponse($expected, 'PUT', '/api/menus/1');

        $result = $this->apiService->updateMenu(1, ['aliment' => 'Viande modifie']);
        $this->assertSame($expected, $result);
    }

    public function testDeleteMenu(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', $this->stringContains('/api/menus/1'))
            ->willReturn($response);

        $this->apiService->deleteMenu(1);
        $this->assertTrue(true);
    }

    public function testRecommanderMenu(): void
    {
        $expected = ['message' => 'Menu recommande pour cette espece'];
        $this->mockResponse($expected, 'POST', '/api/especes/1/menus');

        $result = $this->apiService->recommanderMenu(1, 1);
        $this->assertSame($expected, $result);
    }

    public function testRetirerMenu(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', $this->stringContains('/api/especes/1/menus/1'))
            ->willReturn($response);

        $this->apiService->retirerMenu(1, 1);
        $this->assertTrue(true);
    }

    // ========================
    // GESTIONNAIRES
    // ========================

    public function testLoginGestionnaire(): void
    {
        $expected = ['id' => 1, 'login' => 'admin', 'nom' => 'Admin', 'prenom' => 'Sys', 'role' => 'admin'];
        $this->mockResponse($expected, 'POST', '/api/gestionnaires/login');

        $result = $this->apiService->loginGestionnaire('admin', 'admin');
        $this->assertSame($expected, $result);
    }

    public function testGetGestionnaires(): void
    {
        $expected = [['id' => 1, 'login' => 'admin']];
        $this->mockResponse($expected, 'GET', '/api/gestionnaires');

        $result = $this->apiService->getGestionnaires();
        $this->assertSame($expected, $result);
    }

    public function testCreateGestionnaire(): void
    {
        $expected = ['id' => 2, 'login' => 'user1'];
        $this->mockResponse($expected, 'POST', '/api/gestionnaires');

        $result = $this->apiService->createGestionnaire(['login' => 'user1', 'mot_de_passe' => 'pass']);
        $this->assertSame($expected, $result);
    }

    public function testUpdateGestionnaire(): void
    {
        $expected = ['id' => 1, 'login' => 'admin', 'nom' => 'Modifie'];
        $this->mockResponse($expected, 'PUT', '/api/gestionnaires/1');

        $result = $this->apiService->updateGestionnaire(1, ['nom' => 'Modifie']);
        $this->assertSame($expected, $result);
    }

    public function testDeleteGestionnaire(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', $this->stringContains('/api/gestionnaires/1'))
            ->willReturn($response);

        $this->apiService->deleteGestionnaire(1);
        $this->assertTrue(true);
    }

    // ========================
    // BASE URL
    // ========================

    public function testBaseUrlTrailingSlash(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn([]);

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'http://localhost:8000/api/especes')
            ->willReturn($response);

        $service = new ApiService($httpClient, 'http://localhost:8000/');
        $service->getEspeces();
    }
}
