<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        // Routes publiques (pas besoin d'etre connecte)
        $publicRoutes = ['login', '_preview_error'];
        if (in_array($route, $publicRoutes) || str_starts_with($route ?? '', '_')) {
            return;
        }

        // Verifier si connecte
        $gestionnaire = $request->getSession()->get('gestionnaire');
        if (!$gestionnaire) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('login')));
            return;
        }

        // Routes admin uniquement (gestion des gestionnaires)
        $adminRoutes = ['gestionnaire_index', 'gestionnaire_new', 'gestionnaire_edit', 'gestionnaire_delete'];
        if (in_array($route, $adminRoutes) && ($gestionnaire['role'] ?? '') !== 'admin') {
            $request->getSession()->getFlashBag()->add('danger', 'Acces reserve aux administrateurs.');
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('dashboard')));
        }
    }
}
