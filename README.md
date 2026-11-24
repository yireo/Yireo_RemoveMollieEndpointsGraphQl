# Yireo RemoveMollieEndpointsGraphQl

**Magento 2 module to remove the GraphQL endpoints that are added by the Mollie Payment plugin**

### Installation
First register the sources of this repository as a composer repository. Then, install things:
```bash
composer require yireo/magento2-remove-mollie-endpoints-graph-ql
bin/magento module:enable Yireo_RemoveMollieEndpointsGraphQl Yireo_GraphQlSchemaManipulation
```

**Note that this heavily relies upon the module [Yireo_GraphQlSchemaManipulation](https://github.com/yireo/Yireo_GraphQlSchemaManipulation) which should installed and enabled, when using this module.**

### What is removed
Within the GraphQL schema, every field (of a type, an input or an interface) is removed when:

- its name starts with `mollie` (for instance `molliePaymentMethods`, `mollieProcessTransaction` or `Cart.mollie_available_issuers`);
- its type starts with `Mollie` (for instance `MolliePaymentMethodMeta`);
- its resolver is a `Mollie\` class;
- one of its arguments has a type starting with `Mollie` (for instance `MollieTransactionInput`).

### What is not removed
This module only touches GraphQL. The REST endpoints of Mollie (`/V1/mollie/*`, `/V1/carts/mine/mollie/*` and `/V1/guest-carts/:cartId/mollie/*`, most of them anonymous) and the frontend routes (`mollie/checkout/*`) remain available. To remove the REST endpoints as well, use the module [Yireo_RemoveMollieEndpointsRest](https://github.com/yireo/Yireo_RemoveMollieEndpointsRest).
