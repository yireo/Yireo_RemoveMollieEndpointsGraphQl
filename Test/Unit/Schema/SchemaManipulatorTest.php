<?php

declare(strict_types=1);

namespace Yireo\RemoveMollieEndpointsGraphQl\Test\Unit\Schema;

use Magento\Framework\GraphQl\Config\Element\Argument;
use Magento\Framework\GraphQl\Config\Element\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yireo\RemoveMollieEndpointsGraphQl\Schema\SchemaManipulator;

class SchemaManipulatorTest extends TestCase
{
    #[DataProvider('getFields')]
    public function testManipulateFieldsConfig(Field $field, bool $removed): void
    {
        $config = (new SchemaManipulator())->manipulateFieldsConfig([$field->getName() => $field]);
        $this->assertSame($removed, !isset($config[$field->getName()]));
    }

    public static function getFields(): array
    {
        return [
            'regular field' => [new Field('products', 'Products', false, false, '', 'Magento\\Products'), false],
            'mollie field name' => [new Field('molliePaymentMethods', 'Output', false, false), true],
            'mollie snake case field name' => [new Field('mollie_meta', 'Meta', false, false), true],
            'mollie type' => [new Field('meta', 'MolliePaymentMethodMeta', false, false), true],
            'mollie list type' => [new Field('issuers', '', false, true, 'MollieIssuer'), true],
            'mollie resolver' => [new Field('token', 'String', false, false, '', '\\Mollie\\Payment\\Resolver'), true],
            'mollie argument type' => [
                new Field('start', 'String', false, false, '', '', '', [
                    'input' => new Argument('input', 'MollieTransactionInput', 'MollieTransactionInput', '', false, false),
                ]),
                true,
            ],
            'name containing mollie' => [new Field('notmollie', 'String', false, false), false],
            'resolver containing mollie' => [new Field('foo', 'String', false, false, '', 'Vendor\\Mollie\\Foo'), false],
        ];
    }

    public function testRemovesByConfigKey(): void
    {
        $config = (new SchemaManipulator())->manipulateFieldsConfig(['mollieFoo' => 'bar', 'foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $config);
    }
}
