<?php

declare(strict_types=1);

namespace Yireo\RemoveMollieEndpointsGraphQl\Schema;

use Magento\Framework\GraphQl\Config\Element\Field;
use Yireo\GraphQlSchemaManipulation\Schema\ManipulationInterface;

class SchemaManipulator implements ManipulationInterface
{
    public function manipulateFieldsConfig(array $config): array
    {
        foreach ($config as $fieldName => $field) {
            if (stristr((string)$fieldName, 'mollie')) {
                unset($config[$fieldName]);
                continue;
            }

            if ($field instanceof Field && stristr($field->getTypeName(), 'mollie')) {
                unset($config[$fieldName]);
            }
        }

        return $config;
    }
}
