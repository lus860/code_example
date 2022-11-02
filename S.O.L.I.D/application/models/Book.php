<?php

use interfaces\IItem;
use interfaces\IDiscountable;

class Book implements IItem, IDiscountable
{
    public function setCondition($condition)
    {/*...*/
    }

    public function setPrice($price)
    {/*...*/
    }

    public function applyDiscount($discount)
    {/*...*/
    }

    public function applyPromocode($promocode)
    {/*...*/
    }
}