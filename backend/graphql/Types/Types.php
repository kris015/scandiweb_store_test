<?php

namespace Scandiweb\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ListOfType;

class Types
{
    private static $query;
    private static $mutation;
    private static $category;
    private static $product;
    private static $attributeSet;
    private static $attribute;
    private static $price;
    private static $currency;
    private static $order;
    private static $orderProductInput;
    private static $orderProductAttributeInput;

public static function query($graphQLService): ObjectType
{
    return self::$query ?: (self::$query = new Query($graphQLService));
}

public static function mutation($orderService): ObjectType
{
    return self::$mutation ?: (self::$mutation = new Mutation($orderService));
}

    public static function category(): ObjectType
    {
        return self::$category ?: (self::$category = new ObjectType([
            'name' => 'Category',
            'fields' => [
                'id' => ['type' => Type::nonNull(Type::int())],
                'name' => ['type' => Type::nonNull(Type::string())],
                '__typename' => ['type' => Type::nonNull(Type::string())]
            ]
        ]));
    }

    public static function product(): ObjectType
        {
            return self::$product ?: (self::$product = new ObjectType([
                'name' => 'Product',
                'fields' => [
                    'id' => ['type' => Type::nonNull(Type::string())],
                    'name' => ['type' => Type::nonNull(Type::string())],
                    'inStock' => ['type' => Type::nonNull(Type::boolean())],
                    'gallery' => ['type' => Type::nonNull(Type::listOf(Type::string()))],
                    'description' => ['type' => Type::nonNull(Type::string())],
                    'category' => ['type' => Type::nonNull(Type::string())],
                    'attributes' => ['type' => Type::nonNull(Type::listOf(self::attributeSet()))],
                    'prices' => ['type' => Type::nonNull(Type::listOf(self::price()))],
                    'brand' => ['type' => Type::nonNull(Type::string())],
                    '__typename' => ['type' => Type::nonNull(Type::string())]
                ]
            ]));
        }


    public static function attributeSet(): ObjectType
        {
            return self::$attributeSet ?: (self::$attributeSet = new ObjectType([
                'name' => 'AttributeSet',
                'fields' => [
                    'id' => ['type' => Type::nonNull(Type::int())],
                    'name' => ['type' => Type::nonNull(Type::string())],
                    'type' => ['type' => Type::nonNull(Type::string())],
                    'items' => ['type' => Type::nonNull(Type::listOf(self::attribute()))],
                    '__typename' => ['type' => Type::nonNull(Type::string())]
                ]
            ]));
        }


    public static function attribute(): ObjectType
    {
        return self::$attribute ?: (self::$attribute = new ObjectType([
            'name' => 'Attribute',
            'fields' => [
                'id' => ['type' => Type::nonNull(Type::int())],
                'displayValue' => ['type' => Type::nonNull(Type::string())],
                'value' => ['type' => Type::nonNull(Type::string())],
                '__typename' => ['type' => Type::nonNull(Type::string())]
            ]
        ]));
    }

    public static function price(): ObjectType
    {
        return self::$price ?: (self::$price = new ObjectType([
            'name' => 'Price',
            'fields' => [
                'amount' => ['type' => Type::nonNull(Type::float())],
                'currency' => ['type' => Type::nonNull(self::currency())],
                '__typename' => ['type' => Type::nonNull(Type::string())]
            ]
        ]));
    }

    public static function currency(): ObjectType
    {
        return self::$currency ?: (self::$currency = new ObjectType([
            'name' => 'Currency',
            'fields' => [
                'id' => ['type' => Type::nonNull(Type::int())],
                'label' => ['type' => Type::nonNull(Type::string())],
                'symbol' => ['type' => Type::nonNull(Type::string())],
                '__typename' => ['type' => Type::nonNull(Type::string())]
            ]
        ]));
    }

    public static function order(): ObjectType
    {
        return self::$order ?: (self::$order = new ObjectType([
            'name' => 'Order',
            'fields' => [
                'id' => ['type' => Type::nonNull(Type::int())],
                'createdAt' => ['type' => Type::nonNull(Type::string())]
            ]
        ]));
    }

    public static function orderProductInput(): InputObjectType
        {
            return self::$orderProductInput ?: (self::$orderProductInput = new InputObjectType([
                'name' => 'OrderProductInput',
                'fields' => [
                    'productId' => ['type' => Type::nonNull(Type::string())],
                    'quantity' => ['type' => Type::nonNull(Type::int())],
                    'attributes' => ['type' => Type::nonNull(Type::listOf(self::orderProductAttributeInput()))],
                ]
            ]));
        }


    public static function orderProductAttributeInput(): InputObjectType
    {
        return self::$orderProductAttributeInput ?: (self::$orderProductAttributeInput = new InputObjectType([
            'name' => 'OrderProductAttributeInput',
            'fields' => [
                'attributeId' => ['type' => Type::nonNull(Type::int())],
                'attributeItemId' => ['type' => Type::nonNull(Type::int())]
            ]
        ]));
    }
}