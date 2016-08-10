# Changelog

All Notable changes to `jpmurray/laravel-rrule` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 3.0.0 - 2016-08-10

### Added
- BREAKING CHANGE: The occurence property (returned by `getOccurences()`) now contains a collection with each occurence's start and end. Those start and end are no longer `\DateTime` objects but now `Carbon\Carbon` objects.
- `setFrom()` to give a start to occurences generation
- `getToText()` to access the toText value based on current rule values, on demand, without build()`
- `getOccurences()` to access the occurences based on current rule values, on demand, without build()`

## 2.1.0 - 2016-08-10

### Added
- `setUntil()` method to have occurences until set date. Cannot be used with `setCount()`method.

## 2.0.0 - 2016-08-09

### Deprecated
- BREAKING CHANGE: `save()` method is now renamed `build()`

## 1.0.0 - 2016-08-09

### Added
- Can now generate occurences correctly.

## X.X.X - YYYY-MM-DD (TEMPLATE)

### Added
- Nothing

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
