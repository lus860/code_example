<?php

namespace services\order;

use services\order\contracts\IOrderSource;

class MySQLOrderSource implements IOrderSource
{
    public function load($orderID)
    {/*...*/
    }

    public function save($order)
    {/*...*/
    }

    public function update($order)
    {/*...*/
    }

    public function delete($order)
    {/*...*/
    }
}