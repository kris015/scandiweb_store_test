<?php

namespace Scandiweb\Models;

class Order
{
    public int $id;
    public string $createdAt;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}