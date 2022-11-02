<?php

namespace interfaces;

interface IDiscountable
{
    public function applyDiscount($discount);

    public function applyPromocode($promocode);
}