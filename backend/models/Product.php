<?php

namespace Scandiweb\Models;

class Product
{
    public string $id;
    public string $name;
    public bool $inStock;
    public array $gallery = [];
    public string $description;
    public string $category;
    public array $attributes = [];
    public array $prices = [];
    public string $brand;
    public string $__typename = 'Product';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}