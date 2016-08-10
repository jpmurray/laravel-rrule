<?php

namespace jpmurray\LaravelRrule;

use Carbon\Carbon;
use Recurr\Frequency;
use Recurr\Rule;
use Recurr\Transformer\TextTransformer;

class Recurrence
{
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
    protected $rRuleString;
    protected $rule;

    public $occurences;
    public $toText;
    
    public function __construct()
    {
        $this->rRuleFrequencies = collect([
            "yearly" => Frequency::YEARLY,
            "monthly" => Frequency::MONTHLY,
            "weekly" => Frequency::WEEKLY,
            "daily" => Frequency::DAILY,
            "hourly" => Frequency::HOURLY,
            "minutely" => Frequency::MINUTELY,
            "secondly" => Frequency::SECONDLY,
        ]);

        $this->rRuleByDay = collect([
            'sunday' => 'SU',
            'monday' => 'MO',
            'tuesday' => 'TU',
            'wednesday' => 'WE',
            'thursday' => 'TU',
            'friday' => 'FR',
            'saturday' => 'SA',
        ]);

        $this->rRuleByMonth = collect([
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
    public function setLang($lang = "en")
    {
        $this->requestedLang = $lang;
        return $this;
    }

    /**
     * Set the frequency for Rrule
     * @param Array $requested Requested frequency
     */
    public function setFrequency(string $requested)
    {
        $requested = strtoupper($requested);
        $this->requestedFrequency = "FREQ={$requested};";
        return $this;
    }

    /**
     * Set the count value for Rrule
     * @param int $count The count
     */
    public function setCount(int $count)
    {
        $this->requestedCount = "COUNT={$count};";

        return $this;
    }

    /**
     * Set the interval value for Rrule
     * @param int $interval The interval
     */
    public function setInterval(int $interval)
    {
        $this->requestedInterval = "INTERVAL={$interval};";

        return $this;
    }

    /**
     * Set the days for Rrule
     * @param int $count The interval
     */
    public function setDays(array $days)
    {
        $days = collect($days);

        $values = $days->map(function ($item, $key) {
            return "{$item[1]}{$this->rRuleByDay->get($item[0])}";
        })->toArray();

        $values = implode(',', $values);
        $this->requestedDays = "BYDAY={$values};";
        return $this;
    }

    /**
     * Set the days for Rrule
     * @param int $count The interval
     */
    public function setMonths(array $months)
    {
        $months = collect($months);

        $values = $months->map(function ($item, $key) {
            return $this->rRuleByMonth->get($item);
        })->toArray();

        $values = implode(',', $values);
        $this->requestedMonths = "BYMONTH={$values};";

        return $this;
    }

    /**
     * Set the start date for the recurrence
     * @param Carbon $start Start date
     */
    public function setStart(Carbon $start)
    {
        $this->requestedStart = $start;
        return $this;
    }

    /**
     * Set the end date for the recurrence
     * @param Carbon $end End date
     */
    public function setEnd(Carbon $end)
    {
        $this->requestedEnd = $end;
        return $this;
    }

    /**
     * Create the string for use in Rrule.
     */
    private function setRuleString()
    {
        $this->rRuleString = rtrim("{$this->requestedCount}{$this->requestedFrequency}{$this->requestedInterval}{$this->requestedDays}{$this->requestedMonths}{$this->requestedUntil}{$this->requestedFrom}", ';');
    }

    /**
     * Take the current rule, and make it humand readable in the setted language
     */
    private function setToText()
    {
        $textTransformer = new TextTransformer(
            new \Recurr\Transformer\Translator($this->requestedLang)
        );

        $this->toText = $textTransformer->transform($this->rule);
    }

    /**
     * Set the minimum date for occurence generation.
     * @param Carbon $date The first date possible for occurences
     */
    public function setFrom(Carbon $date)
    {
        $this->requestedFrom = "DTSTART={$date->format('Ymd')};";
        return $this;
    }

    /**
     * Set the maximum date for occurence generation. Cannot be used with `setCount()`
     * @param Carbon $date The last date possible for occurences
     */
    public function setUntil(Carbon $date)
    {
        $this->requestedUntil = "UNTIL={$date->format('Ymd')};";
        return $this;
    }

    /**
     * Will take the current rule and transform it to occurences
     */
    private function setOccurences()
    {
        $transformer = new \Recurr\Transformer\ArrayTransformer();
        $this->occurences = collect($transformer->transform($this->rule));
    }

    /**
     * Creates a Rule object
     */
    private function createRule(){
        $this->setRuleString();
        $this->rule = new Rule($this->rRuleString, $this->requestedStart, $this->requestedEnd);
    }

    /**
     * Will generate a toText from current value and return it
     * @return string A humand readable string of the current rule
     */
    public function getToText(){
        $this->createRule();
        $this->setToText();

        return $this->toText;
    }

    /**
     * Will generate occurences from current value and return them
     * @return string A humand readable string of the current rule
     */
    public function getOccurences(){
        $this->createRule();
        $this->setOccurences();

        return $this->occurences;
    }

    /**
     * Build the occurences from the rule
     */
    public function build()
    {
        $this->createRule();
        $this->setToText();
        $this->setOccurences();
        return $this;
    }
}
