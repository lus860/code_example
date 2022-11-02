<?php

namespace application\controllers;

use application\models\Order;
use services\customer\OrderProcessorTwo;
use services\customer\OrderProcessorOne;

class CustomerController
{
    private $currentOrder = null;

    // The IOrderProcessor interface has been created, which will be
    // implemented by the corresponding classes OrderProcessorTwo,
    // OrderProcessorOne and, depending on the conditions of the task,
    // either OrderProcessorTwo or OrderProcessorOne can be used

    public function buyItems(OrderProcessorTwo $processor)
    {
        if (is_null($this->currentOrder)) {
            return false;
        }

        return $processor->checkout($this->currentOrder);
    }

//    public function buyItems(OrderProcessorOne $processor)
//    {
//        if (is_null($this->currentOrder)) {
//            return false;
//        }
//
//        return $processor->checkout($this->currentOrder);
//    }

    public function addItem($item)
    {
        if (is_null($this->currentOrder)) {
            $this->currentOrder = new Order();
        }
        return $this->currentOrder->addItem($item);
    }

    public function deleteItem($item)
    {
        if (is_null($this->currentOrder)) {
            return false;
        }
        return $this->currentOrder->deleteItem($item);
    }
}