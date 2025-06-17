<?php

namespace Scandiweb\Models;

class Price
{
    public int $id;
    public float $amount;
    public Currency $currency;
    public string $__typename = 'Price';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}