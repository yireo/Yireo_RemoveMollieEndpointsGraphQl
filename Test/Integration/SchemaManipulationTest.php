<?php

declare(strict_types=1);

namespace Yireo\RemoveMollieEndpointsGraphQl\Test\Integration;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InterfaceType as GraphQlInterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Magento\Framework\GraphQl\Config\Element\FieldsFactory;
use Magento\Framework\GraphQl\Config\Element\InterfaceFactory;
use Magento\Framework\GraphQl\Config\Element\InterfaceType;
use Magento\Framework\GraphQl\Schema\SchemaGeneratorInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoAppArea graphql
 */
class SchemaManipulationTest extends TestCase
{
    public function testMollieFieldsAreRemovedFromTypes(): void
    {
        $fieldsFactory = Bootstrap::getObjectManager()->get(FieldsFactory::class);
        $fields = $fieldsFactory->createFromConfigData($this->getFieldsData());

        $this->assertSame(['products'], array_keys($fields));
    }

    public function testMollieFieldsAreRemovedFromInterfaces(): void
    {
        $interfaceFactory = Bootstrap::getObjectManager()->get(InterfaceFactory::class);
        $interface = $interfaceFactory->createFromConfigData([
            'name' => 'ExampleInterface',
            'typeResolver' => 'Example\\TypeResolver',
            'fields' => $this->getFieldsData(),
        ]);

        $this->assertInstanceOf(InterfaceType::class, $interface);
        $this->assertSame('ExampleInterface', $interface->getName());
        $this->assertSame(['products'], array_keys($interface->getFields()));
    }

    public function testSchemaContainsNoMollieFields(): void
    {
        $schema = Bootstrap::getObjectManager()->get(SchemaGeneratorInterface::class)->generate();
        $typeMap = $schema->getTypeMap();
        $this->assertArrayHasKey('Products', $typeMap);

        foreach ($typeMap as $type) {
            $this->assertDoesNotMatchRegularExpression('/^mollie/i', $type->name);
            if (false === $type instanceof ObjectType
                && false === $type instanceof GraphQlInterfaceType
                && false === $type instanceof InputObjectType) {
                continue;
            }

            foreach ($type->getFields() as $field) {
                $label = $type->name . '.' . $field->name;
                $this->assertDoesNotMatchRegularExpression('/^mollie/i', $field->name, $label);
                $this->assertDoesNotMatchRegularExpression(
                    '/^mollie/i',
                    (string)Type::getNamedType($field->getType())?->name,
                    $label
                );

                foreach ($field->args ?? [] as $argument) {
                    $this->assertDoesNotMatchRegularExpression(
                        '/^mollie/i',
                        (string)Type::getNamedType($argument->getType())?->name,
                        $label . '(' . $argument->name . ')'
                    );
                }
            }
        }
    }

    private function getFieldsData(): array
    {
        return [
            ['name' => 'products', 'type' => 'Products', 'arguments' => []],
            ['name' => 'molliePaymentMethods', 'type' => 'MolliePaymentMethodsOutput', 'arguments' => []],
            ['name' => 'mollie_meta', 'type' => 'String', 'arguments' => []],
            ['name' => 'meta', 'type' => 'MolliePaymentMethodMeta', 'arguments' => []],
            [
                'name' => 'token',
                'type' => 'String',
                'resolver' => '\\Mollie\\Payment\\GraphQL\\Resolver\\Checkout\\PaymentToken',
                'arguments' => [],
            ],
            [
                'name' => 'startTransaction',
                'type' => 'String',
                'arguments' => [['name' => 'input', 'type' => 'MollieTransactionInput']],
            ],
        ];
    }
}
