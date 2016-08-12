# Changelog

All Notable changes to `jpmurray/laravel-rrule` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## master - next version
### Added
- Added a `setRuleFromString()` method to set the recurrence object from a correctly formatted rRule string. THIS IS EXPERIMENTAL AND NOT PROPERLY TESTED (yet).
- The `setRuleFromString()` method sets a public attribute to the recurrence object called `rawValues`. This is especially useful when working with forms, this could help set the default value for an edit page. 
- Added `getRawValues()` method to get the corresponding attribute.

## 3.2.0 - 2016-08-11
### Added
- Getters for corresponding setters (ex: `setCount() now have a `getCount()`);
- Some tests

## 3.1.0 - 2016-08-10

### Added
- 'getRruleString()' to access the value of rRuleString based on current rule values, on demand, without build()`

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
