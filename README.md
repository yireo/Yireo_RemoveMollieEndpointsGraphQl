# Yireo RemoveMollieEndpointsGraphQl

**Magento 2 module to remove the GraphQL endpoints that are added by the Mollie Payment plugin**

### Installation
First register the sources of this repository as a composer repository. Then, install things:
```bash
composer require yireo/magento2-remove-mollie-endpoints-graph-ql
bin/magento module:enable Yireo_RemoveMollieEndpointsGraphQl Yireo_GraphQlSchemaManipulation
```

**Note that this heavily relies upon the module [Yireo_GraphQlSchemaManipulation](https://github.com/yireo/Yireo_GraphQlSchemaManipulation) which should installed and enabled, when using this module.**