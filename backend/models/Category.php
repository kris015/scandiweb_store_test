<?php

namespace Scandiweb\Models;

class Category
{
    public int $id;
    public string $name;
    public string $__typename = 'Category';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}