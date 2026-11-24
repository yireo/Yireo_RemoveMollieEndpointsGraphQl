<?php

declare(strict_types=1);

namespace Yireo\RemoveMollieEndpointsGraphQl\Schema;

use Magento\Framework\GraphQl\Config\Element\Argument;
use Magento\Framework\GraphQl\Config\Element\Field;
use Yireo\GraphQlSchemaManipulation\Schema\ManipulationInterface;

class SchemaManipulator implements ManipulationInterface
{
    public function manipulateFieldsConfig(array $config): array
    {
        foreach ($config as $fieldName => $field) {
            if ($this->isMollieName((string)$fieldName)) {
                unset($config[$fieldName]);
                continue;
            }

            if ($field instanceof Field && $this->isMollieField($field)) {
                unset($config[$fieldName]);
            }
        }

        return $config;
    }

    private function isMollieField(Field $field): bool
    {
        if ($this->isMollieName($field->getName()) || $this->isMollieName($field->getTypeName())) {
            return true;
        }

        if (str_starts_with(ltrim($field->getResolver(), '\\'), 'Mollie\\')) {
            return true;
        }

        foreach ($field->getArguments() as $argument) {
            if ($argument instanceof Argument && $this->isMollieName($argument->getTypeName())) {
                return true;
            }
        }

        return false;
    }

    private function isMollieName(string $name): bool
    {
        return stripos($name, 'mollie') === 0;
    }
}
