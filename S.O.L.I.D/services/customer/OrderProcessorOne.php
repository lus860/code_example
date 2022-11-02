<?php

namespace services\customer;

use services\customer\contracts\IOrderProcessor;

class OrderProcessorOne implements IOrderProcessor
{
    public function checkout($order)
    {/*...*/
    }
}