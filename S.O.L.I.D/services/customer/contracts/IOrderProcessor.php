<?php

namespace services\customer\contracts;

interface IOrderProcessor
{
    public function checkout($order);
}