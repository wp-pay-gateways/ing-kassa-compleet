# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]
-

## [2.0.2] - 2019-08-27
- Updated packages.

## [2.0.1] - 2018-12-12
- Use issuer field from core gateway.
- Updated deprecated function calls.

## [2.0.0] - 2018-05-14
- Switched to PHP namespaces.

## [1.0.7] - 2017-05-01
- Fixed issuer not set if payment method is not empty.
- Improved error handling for inactive payment methods.
- Make payment method required.

## [1.0.6] - 2017-04-07
- Only set iDEAL payment method if none set yet.
- Added two extra payment methods.
- Added extra supported payment methods.

## [1.0.5] - 2016-10-20
- Added `payment_status_request` feature support.
- Removed schedule status check event, this will be part of the Pronamic iDEAL plugin.
- Simplified payment methods constants code.
- Added error status.

## [1.0.4] - 2016-06-08
- Simplified the gateay payment start function.

## [1.0.3] - 2016-03-22
- Added webhook listener.
- Added scheduled events to check transaction status.
- Updated gateway settings.

## [1.0.2] - 2016-03-02
- Added get_settings function.
- Moved get_gateway_class() function to the configuration class.
- Removed get_config_class(), no longer required.

## [1.0.1] - 2016-02-03
- Fix fatal error "Can't use method return value in write context".

## 1.0.0 - 2016-02-01
- First release.

[unreleased]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/2.0.2...HEAD
[2.0.2]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/1.0.7...2.0.0
[1.0.7]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/1.0.6...1.0.7
[1.0.6]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/1.0.5...1.0.6
[1.0.5]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/1.0.4...1.0.5
[1.0.4]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/wp-pay-gateways/ing-kassa-compleet/compare/1.0.0...1.0.1
