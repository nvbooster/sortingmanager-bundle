<?php

namespace nvbooster\SortingManagerBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use nvbooster\SortingManagerBundle\ConfigStorage\CookieStorage;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class SaveSortingCookieListener implements EventSubscriberInterface
{
    /**
     * @var CookieStorage
     */
    protected $cookieStorage;

    /**
     * @var number
     */
    protected $cookieExpire;

    /**
     * @param CookieStorage $storage
     * @param number        $cookieExpire
     */
    public function __construct(CookieStorage $storage, $cookieExpire)
    {
        $this->cookieStorage = $storage;
        $this->cookieExpire = $cookieExpire;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->isMasterRequest() && $updates = $this->cookieStorage->getUpdates()) {
            $response = $event->getResponse();

            foreach ($updates as $key => $value) {
                $cookie = new Cookie($key, $value, time() + $this->cookieExpire);
                $response->headers->setCookie($cookie);
            }
        }
    }

    /**
     * {@inheritdoc}
     * @see \Symfony\Component\EventDispatcher\EventSubscriberInterface::getSubscribedEvents()
     */
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::RESPONSE => ['onKernelResponse', -128] ];
    }
}