<?php

declare(strict_types=1);

namespace Yireo\RemoveMollieEndpointsGraphQl\Test\Functional;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ObjectManager\ConfigLoader;
use Magento\Framework\App\State;
use Magento\Framework\GraphQl\Schema\SchemaGeneratorInterface;
use PHPUnit\Framework\TestCase;

class ListGraphQlEndpointsTest extends TestCase
{
    protected function setUp(): void
    {
        $om = ObjectManager::getInstance();
        $configLoader = $om->get(ConfigLoader::class);
        $om->get(State::class)->setAreaCode('graphql');
        $om->configure($configLoader->load('graphql'));
    }
    
    public function testListEndpoints()
    {
        $this->assertGraphQlTypeExists('Products');
        $this->assertGraphQlTypeNotExists('MolliePaymentMethod');
    }
    
    private function assertGraphQlTypeExists(string $endpoint)
    {
        $types = $this->getTypeNames();
        $this->assertContains($endpoint, $types, implode(', ', $types));
    }
    
    private function assertGraphQlTypeNotExists(string $endpoint)
    {
        $types = $this->getTypeNames();
        $this->assertNotContains($endpoint, $types, implode(', ', $types));
    }
    
    private function getTypeNames(): array
    {
        $schemaGenerator = ObjectManager::getInstance()->get(SchemaGeneratorInterface::class);
        $schema = $schemaGenerator->generate();
        $names = [];
        foreach ($schema->getTypeMap() as $type) {
            $names[] = $type->name();
        }
        
        return $names;
    }
}