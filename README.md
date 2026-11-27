# Yireo RemoveMollieEndpointsGraphQl

<!-- badges.specs.start -->
<div class="github-specs-badges">

![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo/magento2-remove-mollie-endpoints-graph-ql)

</div>
<!-- badges.specs.end -->


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

## Current status

<!-- badges.test.start -->
<div class="github-pipeline-badges">

![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_RemoveMollieEndpointsGraphQl/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_RemoveMollieEndpointsGraphQl/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_RemoveMollieEndpointsGraphQl/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_RemoveMollieEndpointsGraphQl/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_RemoveMollieEndpointsGraphQl/compile.yml?label=compile)

</div>
<!-- badges.test.end -->
