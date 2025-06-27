<?php

namespace Scandiweb\Services;

use Scandiweb\Models\Category;
use Scandiweb\Models\Product;
use Scandiweb\Models\Attribute;
use Scandiweb\Models\AttributeItem;
use Scandiweb\Models\Price;
use Scandiweb\Models\Currency;
use PDO;

class GraphQLService
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getCategories(): array
    {
        $stmt = $this->database->getConnection()->query("SELECT * FROM categories");
        $categoriesData = $stmt->fetchAll();

        $categories = [];
        foreach ($categoriesData as $categoryData) {
            $categories[] = new Category($categoryData);
        }

        return $categories;
    }

    public function getProducts(?string $category = null): array
    {
        $query = "SELECT p.*, c.name as category_name FROM products p 
                  JOIN categories c ON p.category_id = c.id";
        
        $params = [];
        
        if ($category && $category !== 'all') {
            $query .= " WHERE c.name = :category";
            $params[':category'] = $category;
        }
        
        $stmt = $this->database->getConnection()->prepare($query);
        $stmt->execute($params);
        $productsData = $stmt->fetchAll();

        $products = [];
        foreach ($productsData as $productData) {
            $product = new Product([
                'id' => $productData['id'],
                'name' => $productData['name'],
                'inStock' => (bool)$productData['inStock'],
                'description' => $productData['description'],
                'category' => $productData['category_name'],
                'brand' => $productData['brand']
            ]);

            // Get gallery images
            $stmt = $this->database->getConnection()->prepare(
                "SELECT image_url FROM product_images WHERE product_id = :product_id"
            );
            $stmt->execute([':product_id' => $productData['id']]);
            $gallery = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            $product->gallery = $gallery;

            // Get prices
            $stmt = $this->database->getConnection()->prepare(
                "SELECT p.id, p.amount, c.id as currency_id, c.label, c.symbol 
                 FROM prices p 
                 JOIN currencies c ON p.currency_id = c.id 
                 WHERE p.product_id = :product_id"
            );
            $stmt->execute([':product_id' => $productData['id']]);
            $pricesData = $stmt->fetchAll();

            $prices = [];
            foreach ($pricesData as $priceData) {
                $currency = new Currency([
                    'id' => $priceData['currency_id'],
                    'label' => $priceData['label'],
                    'symbol' => $priceData['symbol']
                ]);

                $prices[] = new Price([
                    'id' => $priceData['id'],
                    'amount' => (float)$priceData['amount'],
                    'currency' => $currency
                ]);
            }
            $product->prices = $prices;

            // Get attributes
            $stmt = $this->database->getConnection()->prepare(
                "SELECT a.id, a.name, a.type 
                 FROM attributes a 
                 WHERE a.product_id = :product_id"
            );
            $stmt->execute([':product_id' => $productData['id']]);
            $attributesData = $stmt->fetchAll();

            $attributes = [];
            foreach ($attributesData as $attributeData) {
                $attribute = new Attribute([
                    'id' => $attributeData['id'],
                    'name' => $attributeData['name'],
                    'type' => $attributeData['type']
                ]);

                // Get attribute items
                $stmt = $this->database->getConnection()->prepare(
                    "SELECT id, displayValue, value 
                     FROM attribute_items 
                     WHERE attribute_id = :attribute_id"
                );
                $stmt->execute([':attribute_id' => $attributeData['id']]);
                $itemsData = $stmt->fetchAll();

                $items = [];
                foreach ($itemsData as $itemData) {
                    $items[] = new AttributeItem($itemData);
                }

                $attribute->items = $items;
                $attributes[] = $attribute;
            }
            $product->attributes = $attributes;

            $products[] = $product;
        }

        return $products;
    }

    public function getProduct(string $id): ?Product
    {
        $stmt = $this->database->getConnection()->prepare(
            "SELECT p.*, c.name as category_name FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $productData = $stmt->fetch();

        if (!$productData) {
            return null;
        }

        $product = new Product([
            'id' => $productData['id'],
            'name' => $productData['name'],
            'inStock' => (bool)$productData['inStock'],
            'description' => $productData['description'],
            'category' => $productData['category_name'],
            'brand' => $productData['brand']
        ]);

        // Get gallery images
        $stmt = $this->database->getConnection()->prepare(
            "SELECT image_url FROM product_images WHERE product_id = :product_id"
        );
        $stmt->execute([':product_id' => $productData['id']]);
        $gallery = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        $product->gallery = $gallery;

        // Get prices
        $stmt = $this->database->getConnection()->prepare(
            "SELECT p.id, p.amount, c.id as currency_id, c.label, c.symbol 
             FROM prices p 
             JOIN currencies c ON p.currency_id = c.id 
             WHERE p.product_id = :product_id"
        );
        $stmt->execute([':product_id' => $productData['id']]);
        $pricesData = $stmt->fetchAll();

        $prices = [];
        foreach ($pricesData as $priceData) {
            $currency = new Currency([
                'id' => $priceData['currency_id'],
                'label' => $priceData['label'],
                'symbol' => $priceData['symbol']
            ]);

            $prices[] = new Price([
                'id' => $priceData['id'],
                'amount' => (float)$priceData['amount'],
                'currency' => $currency
            ]);
        }
        $product->prices = $prices;

        // Get attributes
        $stmt = $this->database->getConnection()->prepare(
            "SELECT a.id, a.name, a.type 
             FROM attributes a 
             WHERE a.product_id = :product_id"
        );
        $stmt->execute([':product_id' => $productData['id']]);
        $attributesData = $stmt->fetchAll();

        $attributes = [];
        foreach ($attributesData as $attributeData) {
            $attribute = new Attribute([
                'id' => $attributeData['id'],
                'name' => $attributeData['name'],
                'type' => $attributeData['type']
            ]);

            // Get attribute items
            $stmt = $this->database->getConnection()->prepare(
                "SELECT id, displayValue, value 
                 FROM attribute_items 
                 WHERE attribute_id = :attribute_id"
            );
            $stmt->execute([':attribute_id' => $attributeData['id']]);
            $itemsData = $stmt->fetchAll();

            $items = [];
            foreach ($itemsData as $itemData) {
                $items[] = new AttributeItem($itemData);
            }

            $attribute->items = $items;
            $attributes[] = $attribute;
        }
        $product->attributes = $attributes;

        return $product;
    }

    public function getCurrencies(): array
    {
        $stmt = $this->database->getConnection()->query("SELECT * FROM currencies");
        $currenciesData = $stmt->fetchAll();

        $currencies = [];
        foreach ($currenciesData as $currencyData) {
            $currencies[] = new Currency($currencyData);
        }

        return $currencies;
    }
}