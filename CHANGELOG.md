# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.2] - 2020-05-05

### Added

- Add one-to-one relationships from the alert history to the alert type and locations
- Centralize initial landing page in new controller

### Changed

- Add all current observed alerts to landing page
- Clean up demo VUE object
- Change "AlertDict" to "AlertType" for better clarity

## [0.1.0] - 2020-05-03
### Added
- Add Vue.JS to front end
- Install Laravel Echo Server
- Implement Socket.IO
- Add demo Vue component with dynamic updating to test proof of concept on live server

## [0.0.3a] - 2020-04-21
### Changed
- Fixed a missed semi-colon and failed test due to working at 0200

## [0.0.3] - 2020-04-21
### Added
- Improve code commenting
- Add catch-all to prevent exceptions and fatal errors during run-time when an unmatched alert description pattern is read

## [0.0.2] - 2020-04-21
### Added
- Release a more detailed README for the project
- Add Laravel scheduling to run the _**wscan:scanalerts**_ command every five minutes if a master cron job is enabled

## [0.0.1] - 2020-04-20
### Added
- Initial release with basic migrations, and simple commands to populate the tables with the links to the Environment Canada ATOM feeds as well as pull data from them.

### TODO
- Optimize the cost of pulling the alerts from the ATOM feed
- Create front end interactive interface and API feature for third party integration
- Write documentation for scheduling
