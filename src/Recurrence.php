<?php

namespace jpmurray\LaravelRrule;

use Carbon\Carbon;
use Recurr\Frequency;
use Recurr\Rule;
use Recurr\Transformer\TextTransformer;

class Recurrence {
    protected $requestedCount;
    protected $requestedDays;
    protected $requestedEnd;
    protected $requestedFrequency;
    protected $requestedFrom;
    protected $requestedInterval;
    protected $requestedLang;
    protected $requestedMonths;
    protected $requestedStart;
    protected $requestedUntil;
    protected $rRuleFrequencies;
    protected $rRuleByDay;
    protected $rRuleByMonth;
    protected $rule;

    public $occurences;
    public $rRuleString;
    public $toText;

    public function __construct() {
        $this->rRuleFrequencies = new \Illuminate\Support\Collection([
            "yearly" => Frequency::YEARLY,
            "monthly" => Frequency::MONTHLY,
            "weekly" => Frequency::WEEKLY,
            "daily" => Frequency::DAILY,
            "hourly" => Frequency::HOURLY,
            "minutely" => Frequency::MINUTELY,
            "secondly" => Frequency::SECONDLY,
        ]);

        $this->rRuleByDay = new \Illuminate\Support\Collection([
            'sunday' => 'SU',
            'monday' => 'MO',
            'tuesday' => 'TU',
            'wednesday' => 'WE',
            'thursday' => 'TU',
            'friday' => 'FR',
            'saturday' => 'SA',
        ]);

        $this->rRuleByMonth = new \Illuminate\Support\Collection([
            'january' => 1,
            'february' => 2,
            'march' => 3,
            'april' => 4,
            'may' => 5,
            'june' => 6,
            'july' => 7,
            'august' => 8,
            'september' => 9,
            'october' => 10,
            'november' => 11,
            'december' => 12,
        ]);

        $this->setLang();
    }

    /**
     * Set the language that will be used for the result of toText()
     * @param string $lang An ISO-639-1 language code
     */
    public function setLang($lang = "en") {
        $this->requestedLang = $lang;
        return $this;
    }

    /**
     * Get the language that will be used for the result of toText()
     * @return string an ISO-639-1 language code
     */
    public function getLang() {
        return $this->requestedLang;
    }

    /**
     * Set the frequency for Rrule
     * @param Array $requested Requested frequency
     */
    public function setFrequency(string $requested) {
        $requested = strtoupper($requested);
        $this->requestedFrequency = "FREQ={$requested};";
        return $this;
    }

    /**
     * Get the frequency value for Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getFrequency() {
        return $this->requestedFrequency;
    }

    /**
     * Set the count value for Rrule
     * @param int $count The count
     */
    public function setCount(int $count) {
        $this->requestedCount = "COUNT={$count};";

        return $this;
    }

    /**
     * Get the count value for Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getCount() {
        return $this->requestedCount;
    }

    /**
     * Set the interval value for Rrule
     * @param int $interval The interval
     */
    public function setInterval(int $interval) {
        $this->requestedInterval = "INTERVAL={$interval};";

        return $this;
    }

    /**
     * Get the frequency value for Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getInterval() {
        return $this->requestedInterval;
    }

    /**
     * Set the days for Rrule
     * @param int $count The interval
     */
    public function setDays(array $days) {

        $days = new \Illuminate\Support\Collection($days);

        $values = $days->map(function ($item, $key) {
            $day = (strlen($item[0]) > 2 ? $this->rRuleByDay->get($item[0]) : $item[0]);
            return "{$item[1]}{$day}";
        })->toArray();

        $values = implode(',', $values);
        $this->requestedDays = "BYDAY={$values};";
        return $this;
    }

    /**
     * Get the days value for Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getDays() {
        return $this->requestedDays;
    }

    /**
     * Set the days for Rrule
     * @param int $count The interval
     */
    public function setMonths(array $months) {
        $months = new \Illuminate\Support\Collection($months);

        $values = $months->map(function ($item, $key) {
            return $this->rRuleByMonth->get($item);
        })->toArray();

        $values = implode(',', $values);
        $this->requestedMonths = "BYMONTH={$values};";

        return $this;
    }

    /**
     * Get the months value for Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getMonths() {
        return $this->requestedMonths;
    }

    /**
     * Set the start date for the recurrence
     * @param Carbon $start Start date
     */
    public function setStart(Carbon $start) {
        $this->requestedStart = $start;
        return $this;
    }

    /**
     * Get the start date value for Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getStart() {
        return $this->requestedStart;
    }

    /**
     * Set the end date for the recurrence
     * @param Carbon $end End date
     */
    public function setEnd(Carbon $end) {
        $this->requestedEnd = $end;
        return $this;
    }

    /**
     * Get the end date value for Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getEnd() {
        return $this->requestedEnd;
    }

    /**
     * Create the string for use in Rrule.
     */
    private function setRuleString() {
        $this->rRuleString = rtrim("{$this->requestedCount}{$this->requestedFrequency}{$this->requestedInterval}{$this->requestedDays}{$this->requestedMonths}{$this->requestedUntil}{$this->requestedFrom}", ';');
    }

    /**
     * Will return the Rrule string from current rules.
     * @return string A Rrule string
     */
    public function getRruleString() {
        $this->createRule();

        return $this->rRuleString;
    }

    /**
     * Take the current rule, and make it humand readable in the setted language
     */
    private function setToText() {
        $textTransformer = new TextTransformer(
            new \Recurr\Transformer\Translator($this->requestedLang)
        );

        $this->toText = $textTransformer->transform($this->rule);
    }

    /**
     * Will generate a toText from current value and return it
     * @return string A humand readable string of the current rule
     */
    public function getToText() {
        $this->createRule();
        $this->setToText();

        return $this->toText;
    }

    /**
     * Set the minimum date for occurence generation.
     * @param Carbon $date The first date possible for occurences
     */
    public function setFrom(Carbon $date) {
        $this->requestedFrom = "DTSTART={$date->format('Ymd')};";
        return $this;
    }

    /**
     * Get the string for use in Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getFrom() {
        return $this->requestedFrom;
    }

    /**
     * Set the maximum date for occurence generation. Cannot be used with `setCount()`
     * @param Carbon $date The last date possible for occurences
     */
    public function setUntil(Carbon $date) {
        $this->requestedUntil = "UNTIL={$date->format('Ymd')};";
        return $this;
    }

    /**
     * Get the string for use in Rrule
     * @return string A formatted string for use in Rrule
     */
    public function getUntil() {
        return $this->requestedUntil;
    }

    /**
     * Will take the current rule and transform it to occurences
     */
    private function setOccurences() {
        $transformer = new \Recurr\Transformer\ArrayTransformer();
        $occurenceCollection = new \Illuminate\Support\Collection($transformer->transform($this->rule));

        $this->occurences = $occurenceCollection->collapse()->map(function ($item, $key) {
            return (object) [
                'start' => Carbon::instance($item->getStart()),
                'end' => Carbon::instance($item->getEnd()),
            ];
        });
    }

    /**
     * Will generate occurences from current value and return them
     * @return string A humand readable string of the current rule
     */
    public function getOccurences() {
        $this->createRule();
        $this->setOccurences();

        return $this->occurences;
    }

    /**
     * EXPERIMENTAL: Set the recurrence object accordning to a rRule highlight_string
     * @param string $string An rRule string
     */
    public function setRuleFromString($string) {
        $values = new \Illuminate\Support\Collection(explode(';', $string));

        $values = $values->map(function ($item, $key) {
            return new \Illuminate\Support\Collection(explode('=', $item));
        });

        $values->each(function ($item, $key) {

            if ($item[0] == "UNTIL") {
                $this->setUntil(Carbon::parse($item[1]));
            }

            if ($item[0] == "DTSTART") {
                $this->setFrom(Carbon::parse($item[1]));
            }

            if ($item[0] == "FREQ") {
                $this->setFrequency($item[1]);
            }

            if ($item[0] == "INTERVAL") {
                $this->setInterval($item[1]);
            }

            if ($item[0] == "BYDAY") {
                $days = new \Illuminate\Support\Collection(explode(',', $item[1]));

                $byDays = $days->map(function ($item, $key) {
                    $day = substr($item, -2);
                    $dayInt = str_replace($day, '', $item);
                    return [$day, $dayInt];
                })->toArray();

                $this->setDays($byDays);
            }
        });
        $this->createRule();
    }

    /**
     * Creates a Rule object
     */
    private function createRule() {
        $this->setRuleString();
        $this->rule = new Rule($this->rRuleString, $this->requestedStart, $this->requestedEnd);
    }

    /**
     * Build the occurences from the rule
     */
    public function build() {
        $this->createRule();
        $this->setToText();
        $this->setOccurences();
        return $this;
    }
}
