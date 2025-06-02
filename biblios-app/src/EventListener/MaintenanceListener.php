<?php

namespace App\EventListener;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class MaintenanceListener
{
    public const IS_MAINTENANCE = false;
    public function __construct(private readonly Environment $twig)
    {

    }

    #[AsEventListener(event: 'kernel.request', priority: 100)]
    public function onRequestEvent(RequestEvent $event): void
    {
        if (self::IS_MAINTENANCE) {
            $response = new Response(
                $this->twig->render('maintenance.html.twig')
            );
            $event->setResponse($response);
        } 
    }
}
