<?php

declare(strict_types=1);

namespace Yireo\RemoveMollieEndpointsGraphQl\Schema;

use Yireo\GraphQlSchemaManipulation\Schema\ManipulationInterface;

class SchemaManipulator implements ManipulationInterface
{
    public function manipulateResolvedTypes(array $resolvedTypes): array
    {
        foreach ($resolvedTypes as $resolvedTypeIndex => $type) {
            if (stristr($type->name(), 'Mollie')) {
                unset($resolvedTypes[$resolvedTypeIndex]);
            }
        }
        
        return $resolvedTypes;
    }
}