<?php

namespace services\order\contracts;

interface IOrderSource
{
    public function load($orderID);

    public function save($order);

    public function update($order);

    public function delete($order);
}