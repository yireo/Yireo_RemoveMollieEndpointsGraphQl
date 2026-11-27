# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 27 November 2026
### Added
- Remove the GraphQL fields, types and endpoints added by the Mollie Payment extension from the GraphQL schema

### Changed
- Requires `yireo/magento2-graph-ql-schema-manipulation` 0.0.3+, which also strips Mollie fields from interfaces
- The schema manipulator is only loaded in the `graphql` area, and the module loads after `Magento_GraphQl`

### Fixed
- Mollie fields are now removed through `manipulateFieldsConfig()` of `ManipulationInterface`
- Fields resolved by a `Mollie\` class, or taking a `Mollie*` input argument, are removed too
- Only field names starting with `mollie` match, so unrelated fields that merely contain the word are kept
