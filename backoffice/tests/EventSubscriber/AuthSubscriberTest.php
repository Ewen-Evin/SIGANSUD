<?php

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\AuthSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthSubscriberTest extends TestCase
{
    private AuthSubscriber $subscriber;
    private UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->urlGenerator->method('generate')
            ->willReturnMap([
                ['login', [], UrlGeneratorInterface::ABSOLUTE_PATH, '/login'],
                ['dashboard', [], UrlGeneratorInterface::ABSOLUTE_PATH, '/'],
            ]);

        $this->subscriber = new AuthSubscriber($this->urlGenerator);
    }

    private function createEvent(string $route, bool $isMainRequest = true, ?array $gestionnaire = null): RequestEvent
    {
        $request = new Request();
        $request->attributes->set('_route', $route);

        $session = new Session(new MockArraySessionStorage());
        if ($gestionnaire !== null) {
            $session->set('gestionnaire', $gestionnaire);
        }
        $request->setSession($session);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $requestType = $isMainRequest ? HttpKernelInterface::MAIN_REQUEST : HttpKernelInterface::SUB_REQUEST;

        return new RequestEvent($kernel, $request, $requestType);
    }

    public function testGetSubscribedEvents(): void
    {
        $events = AuthSubscriber::getSubscribedEvents();
        $this->assertArrayHasKey(KernelEvents::REQUEST, $events);
        $this->assertSame('onKernelRequest', $events[KernelEvents::REQUEST]);
    }

    public function testRouteLoginAccessibleSansConnexion(): void
    {
        $event = $this->createEvent('login');
        $this->subscriber->onKernelRequest($event);
        $this->assertNull($event->getResponse());
    }

    public function testRoutePriveeRedirigeVersLoginSiNonConnecte(): void
    {
        $event = $this->createEvent('dashboard');
        $this->subscriber->onKernelRequest($event);

        $response = $event->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/login', $response->getTargetUrl());
    }

    public function testRoutePriveeAccessibleSiConnecte(): void
    {
        $event = $this->createEvent('dashboard', true, [
            'id' => 1, 'login' => 'admin', 'role' => 'admin',
        ]);
        $this->subscriber->onKernelRequest($event);
        $this->assertNull($event->getResponse());
    }

    public function testRouteAdminRedirigeGestionnaireSansRoleAdmin(): void
    {
        $event = $this->createEvent('gestionnaire_index', true, [
            'id' => 2, 'login' => 'user', 'role' => 'gestionnaire',
        ]);
        $this->subscriber->onKernelRequest($event);

        $response = $event->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/', $response->getTargetUrl());
    }

    public function testRouteAdminAccessibleAvecRoleAdmin(): void
    {
        $event = $this->createEvent('gestionnaire_index', true, [
            'id' => 1, 'login' => 'admin', 'role' => 'admin',
        ]);
        $this->subscriber->onKernelRequest($event);
        $this->assertNull($event->getResponse());
    }

    public function testSousRequeteIgnoree(): void
    {
        $event = $this->createEvent('dashboard', false);
        $this->subscriber->onKernelRequest($event);
        $this->assertNull($event->getResponse());
    }

    public function testRoutesAdminProtegees(): void
    {
        $adminRoutes = ['gestionnaire_index', 'gestionnaire_new', 'gestionnaire_edit', 'gestionnaire_delete'];

        foreach ($adminRoutes as $route) {
            $event = $this->createEvent($route, true, [
                'id' => 2, 'login' => 'user', 'role' => 'gestionnaire',
            ]);
            $this->subscriber->onKernelRequest($event);
            $this->assertInstanceOf(
                RedirectResponse::class,
                $event->getResponse(),
                "La route $route devrait etre protegee pour les non-admin"
            );
        }
    }

    public function testRouteFrameworkIgnoree(): void
    {
        $event = $this->createEvent('_wdt');
        $this->subscriber->onKernelRequest($event);
        $this->assertNull($event->getResponse());
    }

    public function testFlashMessageSurAccesAdminRefuse(): void
    {
        $event = $this->createEvent('gestionnaire_index', true, [
            'id' => 2, 'login' => 'user', 'role' => 'gestionnaire',
        ]);
        $this->subscriber->onKernelRequest($event);

        $flashes = $event->getRequest()->getSession()->getFlashBag()->get('danger');
        $this->assertNotEmpty($flashes);
        $this->assertStringContainsString('administrateurs', $flashes[0]);
    }
}
