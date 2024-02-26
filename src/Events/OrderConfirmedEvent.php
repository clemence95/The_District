<?php

// src/Events/OrderConfirmedEvent.php

namespace App\Events;

use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Commande;

class OrderConfirmedEvent extends Event
{
    private $order;

    public function __construct(Commande $order)
    {
        $this->order = $order;
    }

    public function getOrder(): Commande
    {
        return $this->order;
    }
}
