<?php

namespace services\customer;

use services\customer\contracts\IOrderProcessor;

class OrderProcessorTwo implements IOrderProcessor
{
    public function checkout($order)
    {/*...*/
    }
}