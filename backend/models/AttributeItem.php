<?php

namespace Scandiweb\Models;

class AttributeItem
{
    public int $id;
    public string $displayValue;
    public string $value;
    public string $__typename = 'Attribute';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}