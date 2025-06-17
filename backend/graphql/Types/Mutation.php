<?php

namespace Scandiweb\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use Scandiweb\Services\OrderService;

class Mutation extends ObjectType
{
    public function __construct(OrderService $orderService)
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => Types::order(),
                    'args' => [
                        'products' => [
                            'type' => Type::nonNull(Type::listOf(Types::orderProductInput()))
                        ]
                    ],
                    'resolve' => function($root, $args) use ($orderService) {
                        return $orderService->createOrder($args['products']);
                    }
                ]
            ]
        ]);
    }
}