<?php

namespace application\models;

use services\order\MySQLOrderSource;
use services\order\ApiOrderSource;

class Order
{

    private $source;

    // The IOrderSource interface has been created, which will be
    // implemented by the corresponding classes MySQLOrderSource,
    // ApiOrderSource and, depending on the conditions of the task,
    // either MySQLOrderSource or ApiOrderSource can be used

    public function setSource(MySQLOrderSource $source)
    {
        $this->source = $source;
    }

//    public function setSource(ApiOrderSource $source)
//    {
//        $this->source = $source;
//    }

    public function load($orderID)
    {
        return $this->source->load($orderID);
    }

    public function save($order)
    {/*...*/
    }

    public function update($order)
    {/*...*/
    }

}