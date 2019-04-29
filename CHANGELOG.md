# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [Unreleased]

## [0.7.0] - ...
### Changed
 - removed method `getProgressBarWidth()`
    
## [0.6.0] - ...
### Changed
- Exception type is shown with message
- In `ReportFormatter` renamed method `getString()` to `process()` 

### Added
- added `BenchmarkFunctionFormatterInterface`

## [0.5.2] - 2019-03-03
### Changed
- dependencies versions up

## 0.5.1 - 2019-02-21
### Deprecated
- method `getReport()` for `Reportable` trait, use `report()`

## 0.5.0 - 2019-02-20

### Changed
- method `progressBar()` renamed to `showProgressBy()`
- BenchmarkReportFormatter: if results of all tested(in section) functions are equal result is printed only once
```
All returns are equal: 
integer(3) 
```
- formatting of `stat()` string
```
Done in: 5.4s
Memory: 1.06MB(1.10MB) Real: 2.00MB(2.00MB)
```

### Added
- `report()` method alias of `getReport()`
- `BenchmarkSymfonyPB::DEFAULT_PROGRESSBAR_FORMAT` constant

## [0.4.1-BETA2] - 2019-02-17

## 0.3.3-BETA1 - 2019-02-14

## 0.2.0-ALPHA1 - 2019-01-26

## 0.1.0-RC2 - 2019-01-04

## 0.0.15 - 2018-11-29


[Unreleased]: https://github.com/alecrabbit/php-simple-profiler/compare/0.7.1-ALPHA.1...HEAD
[0.5.2]: https://github.com/alecrabbit/php-simple-profiler/compare/0.4.1-BETA2...0.5.2
[0.4.1-BETA2]: https://github.com/alecrabbit/php-simple-profiler/compare/0.3.3-BETA1...0.4.1-BETA2