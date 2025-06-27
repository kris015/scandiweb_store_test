<?php

namespace Scandiweb\Models;

class Currency
{
    public int $id;
    public string $label;
    public string $symbol;
    public string $__typename = 'Currency';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}