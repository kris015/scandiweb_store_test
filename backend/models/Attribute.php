<?php

namespace Scandiweb\Models;

class Attribute
{
    public int $id;
    public string $name;
    public string $type;
    public string $__typename = 'AttributeSet';
    public array $items = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}