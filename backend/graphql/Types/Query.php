<?php

namespace Scandiweb\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use Scandiweb\Services\GraphQLService;

class Query extends ObjectType
{
    public function __construct(GraphQLService $graphQLService)
    {
        parent::__construct([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::listOf(Types::category()),
                    'resolve' => function() use ($graphQLService) {
                        return $graphQLService->getCategories();
                    }
                ],
                'products' => [
                    'type' => Type::listOf(Types::product()),
                    'args' => [
                        'category' => ['type' => Type::string()]
                    ],
                    'resolve' => function($root, $args) use ($graphQLService) {
                        return $graphQLService->getProducts($args['category'] ?? null);
                    }
                ],
                'product' => [
                    'type' => Types::product(),
                    'args' => [
                        'id' => ['type' => Type::nonNull(Type::string())]
                    ],
                    'resolve' => function($root, $args) use ($graphQLService) {
                        return $graphQLService->getProduct($args['id']);
                    }
                ],
                'currencies' => [
                    'type' => Type::listOf(Types::currency()),
                    'resolve' => function() use ($graphQLService) {
                        return $graphQLService->getCurrencies();
                    }
                ]
            ]
        ]);
    }
}