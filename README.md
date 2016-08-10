# jpmurray/laravel-rrule

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A simple helper to generate date occurences more fluently, using [simshaun/recurr](https://github.com/simshaun/recurr/).

## Nota Bene
This is my first package. I actually built this to get to gain a bit more know-how about packages. It might be useful. It might not. It might be badly constructed. It might not. Honestly, I'll try to maintain and improve it over time, but please bear with me as it's been built more to learn that to be the perfect package.

And on that note: PRs are hapilly welcomed!

## Roadmap
- Implement `until` in rules (and other rules that aren't done yet!).
- Arrange all of this with facades?
- Provide controller and routes so we can get the content of `toText` from an AJAX call.
- Form element generator to manage all the possible rules, in multiple language.

## Install

Via Composer

``` bash
$ composer require jpmurray/laravel-rrule
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

[ico-version]: https://img.shields.io/packagist/v/jpmurray/laravel-rrule.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jpmurray/laravel-rrule/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jpmurray/laravel-rrule.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jpmurray/laravel-rrule.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jpmurray/laravel-rrule.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jpmurray/laravel-rrule
[link-travis]: https://travis-ci.org/jpmurray/laravel-rrule
[link-scrutinizer]: https://scrutinizer-ci.com/g/jpmurray/laravel-rrule/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jpmurray/laravel-rrule
[link-downloads]: https://packagist.org/packages/jpmurray/laravel-rrule
[link-author]: https://github.com/jpmurray
[link-contributors]: ../../contributors
