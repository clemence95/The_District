<?php

// src/EventSubscriber/OrderConfirmationSubscriber.php


namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events\OrderConfirmedEvent;
use App\Service\OrderConfirmationMailService;

class OrderConfirmationSubscriber implements EventSubscriberInterface
{
    private $orderConfirmationMailService;

    public function __construct(OrderConfirmationMailService $orderConfirmationMailService)
    {
        $this->orderConfirmationMailService = $orderConfirmationMailService;
    }

    public static function getSubscribedEvents()
    {
        return [
            OrderConfirmedEvent::class => 'sendOrderConfirmationEmail',
        ];
    }

    public function sendOrderConfirmationEmail(OrderConfirmedEvent $event)
    {
        $order = $event->getOrder();

        // Utilisez le service de messagerie dédié à la confirmation de commande
        $this->orderConfirmationMailService->sendOrderConfirmationEmail($order);
    }
}

