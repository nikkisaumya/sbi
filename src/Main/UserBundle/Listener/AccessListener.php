<?php
namespace Main\UserBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;


class AccessListener
{
    private$security;
    private $router;

    public function __construct($security, $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $url = $this->router->generate('dashboard');
            $event->setResponse(new RedirectResponse($url));
        }
    }
}