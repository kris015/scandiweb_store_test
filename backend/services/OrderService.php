<?php

namespace Scandiweb\Services;

use Scandiweb\Models\Order;
use PDO;

class OrderService
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function createOrder(array $products): Order
{
    $connection = $this->database->getConnection();
    
    try {
        $connection->beginTransaction();

        // Create order
        $stmt = $connection->prepare("INSERT INTO orders (created_at) VALUES (NOW())"); // Dodaj created_at
        $stmt->execute();
        $orderId = $connection->lastInsertId();

        // Add order items
        foreach ($products as $product) {
            $stmt = $connection->prepare(
                "INSERT INTO order_items (order_id, product_id, quantity) 
                 VALUES (:order_id, :product_id, :quantity)"
            );
            $stmt->execute([
                ':order_id' => $orderId,
                ':product_id' => $product['productId'],
                ':quantity' => $product['quantity']
            ]);
            $orderItemId = $connection->lastInsertId();

            // Add order item attributes
            foreach ($product['attributes'] as $attribute) {
                $stmt = $connection->prepare(
                    "INSERT INTO order_item_attributes 
                     (order_item_id, attribute_id, attribute_item_id) 
                     VALUES (:order_item_id, :attribute_id, :attribute_item_id)"
                );
                $stmt->execute([
                    ':order_item_id' => $orderItemId,
                    ':attribute_id' => $attribute['attributeId'],
                    ':attribute_item_id' => $attribute['attributeItemId']
                ]);
            }
        }

        $connection->commit();

        // Vraćamo order sa createdAt vrednošću
        return new Order([
            'id' => $orderId,
            'createdAt' => date('Y-m-d H:i:s') // ili dohvati iz base ako želiš
        ]);
        
    } catch (PDOException $e) {
        $connection->rollBack();
        throw new PDOException("Order creation failed: " . $e->getMessage());
    }
}
}
