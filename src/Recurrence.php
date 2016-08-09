<?php

namespace jpmurray\LaravelRrule;

use Carbon\Carbon;
use Recurr\Frequency;
use Recurr\Rule;
use Recurr\Transformer\TextTransformer;

class Recurrence {
    protected $lang;
    protected $rRuleFrequencies;
    protected $rRuleByDay;
    protected $requestedFrequency;
    protected $requestedCount;
    protected $requestedInterval;
    protected $requestedDays;
    protected $requestedStart;
    protected $requestedEnd;
    public $toText;
    public $rule;
    public $rRuleString;

    public function __construct() {
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

        $this->setLang();
    }

    public function setLang($lang = "en")
    {
        $this->lang = $lang;
    }

    /**
     * Set the frequency for Rrule
     * @param Array $requested Requested frequency
     */
    public function setFrequency(string $requested) {
        $requested = strtoupper($requested);
        //$this->requestedFrequency = "FREQ={$this->rRuleFrequencies->get($requested)};";
        $this->requestedFrequency = "FREQ={$requested};";
        return $this;
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
     * Set the interval value for Rrule
     * @param int $interval The interval
     */
    public function setInterval(int $interval) {
        $this->requestedInterval = "INTERVAL={$interval};";

        return $this;
    }

    /**
     * Set the days for Rrule
     * @param int $count The interval
     */
    public function setDays(Array $days) {
        $days = collect($days);

        $values = $days->map(function ($item, $key) {
            return "{$item[1]}{$this->rRuleByDay->get($item[0])}";
        })->toArray();

        $values = implode(',', $values);
        $this->requestedDays = "BYDAY={$values};";

        return $this;
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
     * Set the end date for the recurrence
     * @param Carbon $end End date
     */
    public function setEnd(Carbon $end) {
        $this->requestedEnd = $end;
        return $this;
    }

    /**
     * Create a Rule object
     * @return [type] [description]
     */
    public function save() {
        $this->setRuleString();        
        $this->rule = new Rule($this->rRuleString, $this->requestedStart, $this->requestedEnd);

        $this->setToText();
        return $this;
    }

    /**
     * Create the string for use in Rrule.
     */
    private function setRuleString() {
        $this->rRuleString = rtrim("{$this->requestedCount}{$this->requestedFrequency}{$this->requestedInterval}{$this->requestedDays}", ';');
    }

    private function setToText()
    {
        $textTransformer = new TextTransformer();
        $this->toText = $textTransformer->transform($this->rule);
    }

}
