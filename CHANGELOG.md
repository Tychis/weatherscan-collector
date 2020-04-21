# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.2] - 2020-04-21
### Added
- Release a more detailed README for the project
- Add Laravel scheduling to run the _**wscan:scanalerts**_ command every five minutes if a master cron job is enabled

### TODO
- Improve and optimize the cost of pulling the alerts from the ATOM feed
- Create front end interactive interface and API feature for third party integration
- Write documentation for scheduling


## [0.0.1] - 2020-04-20
### Added
- Initial release with basic migrations, and simple commands to populate the tables with the links to the Environment Canada ATOM feeds as well as pull data from them.

### TODO
- Develop proper README and introduction to the project
- Improve and optimize the cost of pulling the alerts from the ATOM feed
- Create front end interactive interface and API feature
