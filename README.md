# LaravelRrule

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A simple helper to generate date occurences more fluently, using [simshaun/recurr](https://github.com/simshaun/recurr/).

## Install

Via Composer

``` bash
$ composer require jpmurray/LaravelRrule
```

## Usage

``` php
$recurrence = new Recurrence();

//of course, you can chain all those methods!
$recurrence->setFrequency('weekly'); // Either one of `yearly`, `monthly`, `weekly`, `daily`, `hourly`, `minutly`, `secondly`
$recurrence->setCount(20); // the number of occurences we want
$recurrence->setInterval(1); // every Nth time
$recurrence->setStart(Carbon::parse('August 9th 2016 21:18:00')); // a carbon object for when to start the occurences
$recurrence->setEnd(Carbon::parse('August 9th 2016 22:00:10')); // a carbon object for when to end the occurences
$recurrence->setDays([
	['sunday', null],
	['tuesday', -2],
	['friday', 3],
]); // the first is the day of the occurence, the other is the position (eg: -2: second to last; 3: third; null: not set)
$recurrence->setMonths([
	'january', 'march', 'october', 'december'
]); // months of the occurences
$recurrence->setLang('fr'); // for output to text. Defaults to english. Accepts ISO 639-1 language codes
$recurrence->save(); //will save and generate the outputs
```
Once the object has been saved, we can access the result like this (examples is set to above values):

```php
$recurrence->toText; // "weekly in January, March, October and December on the Sunday, 2nd to the last Tuesday and 3rd Friday for 5 times"
$recurrence->occurences; // returns a collection of Datetime object for each occurence
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

NO TESTS AT THE MOMENT. PRs WELCOMED.
``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email himself@jpmurray.net instead of using the issue tracker.

## Credits

- [Jean-Philippe Murray][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jpmurray/LaravelRrule.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jpmurray/LaravelRrule/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jpmurray/LaravelRrule.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jpmurray/LaravelRrule.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jpmurray/LaravelRrule.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jpmurray/LaravelRrule
[link-travis]: https://travis-ci.org/jpmurray/LaravelRrule
[link-scrutinizer]: https://scrutinizer-ci.com/g/jpmurray/LaravelRrule/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jpmurray/LaravelRrule
[link-downloads]: https://packagist.org/packages/jpmurray/LaravelRrule
[link-author]: https://github.com/jpmurray
[link-contributors]: ../../contributors
