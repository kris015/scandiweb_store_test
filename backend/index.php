<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/vendor/autoload.php';

use Scandiweb\Services\Database;
use Scandiweb\Services\GraphQLService;
use Scandiweb\Services\OrderService;
use Scandiweb\GraphQL\Types\Types;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;

try {
    // Load configuration
    $config = require __DIR__ . '/config/database.php';

    // Initialize services
    $database = new Database($config);
    $graphQLService = new GraphQLService($database);
    $orderService = new OrderService($database);

    // Parse incoming request
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'] ?? '';
    $variables = $input['variables'] ?? null;

    // Create GraphQL schema
    $schema = new Schema([
        'query' => Types::query($graphQLService),
        'mutation' => Types::mutation($orderService)
    ]);

    // Execute GraphQL query
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'errors' => [
            [
                'message' => $e->getMessage()
            ]
        ]
    ];
}

echo json_encode($output);