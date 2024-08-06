<?php

declare(strict_types=1);

namespace Yireo\RemoveMollieEndpointsGraphQl\Test\Functional;

use Laminas\Http\Header\ContentType;
use Laminas\Http\Headers;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ObjectManager\ConfigLoader;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\State;
use Magento\Framework\Serialize\SerializerInterface;
use PHPUnit\Framework\TestCase;

class GraphQlQueryTest extends TestCase
{
    public function testIntrospectionForRemovedEndpoints()
    {
        $query = file_get_contents(__DIR__.'/fixtures/introspection.graphql');
        $data = $this->getGraphQlQueryData($query);
        $this->assertNotEmpty($data['data']['__schema']);
        $this->assertGraphQlTypeExists('Products', $data);
        $this->assertGraphQlTypeNotExists('MolliePaymentMethod', $data);
    }
    
    public function testOriginalEndpointForRemovedEndpoints()
    {
        $query = file_get_contents(__DIR__.'/fixtures/molliePaymentMethods.graphql');
        $data = $this->getGraphQlQueryData($query);
        $this->assertEmpty($data['data']['molliePaymentMethods']);
        $this->assertNotEmpty($data['errors']);
    }
    
    private function assertGraphQlTypeExists(string $endpoint, array $data)
    {
        $found = false;
        foreach ($data['data']['__schema']['types'] as $type) {
            if ($type['name'] === $endpoint) {
                $found = true;
                break;
            }
        }
        
        $this->assertTrue($found, var_export($data['data']['__schema']['types'], true));
    }
    
    private function assertGraphQlTypeNotExists(string $endpoint, array $data)
    {
        $found = false;
        foreach ($data['data']['__schema']['types'] as $type) {
            if ($type['name'] === $endpoint) {
                $found = true;
                break;
            }
        }
        
        $this->assertFalse($found, var_export($data['data']['__schema']['types'], true));
    }
    
    private function getGraphQlQueryData(string $query): array
    {
        ObjectManager::getInstance()->get(State::class)->setAreaCode('frontend');
        $om = ObjectManager::getInstance();
        $configLoader = $om->get(ConfigLoader::class);
        $om->get(State::class)->setAreaCode('graphql');
        $om->configure($configLoader->load('graphql'));
        
        $serializer = ObjectManager::getInstance()->get(SerializerInterface::class);
        $httpRequest = ObjectManager::getInstance()->create(Http::class);
        $httpRequest->setMethod('POST');
        $httpRequest->setQueryValue('query', $query);
        
        $content = [
            'query' => $query,
            'variables' => !empty($variables) ? $serializer->serialize($variables) : null,
            'operationName' => !empty($operation) ? $operation : null
        ];
        $httpRequest->setContent($serializer->serialize($content));
        
        $headers = new Headers();
        $headers->addHeader(new ContentType('application/json'));
        $httpRequest->setHeaders($headers);

        $controller = ObjectManager::getInstance()->get(\Magento\GraphQl\Controller\GraphQl::class);
        $response = $controller->dispatch($httpRequest);
        $data = json_decode($response->getContent(), true);
        
        $this->assertNotEmpty($data);
        $this->assertNotEmpty($data['data']);
        return $data;
    }
}