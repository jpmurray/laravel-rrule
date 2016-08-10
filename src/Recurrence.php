<?php

namespace jpmurray\LaravelRrule;

use Carbon\Carbon;
use Recurr\Frequency;
use Recurr\Rule;
use Recurr\Transformer\TextTransformer;

class Recurrence
{
    protected $lang;
    protected $rRuleFrequencies;
    protected $rRuleByDay;
    protected $rRuleByMonth;
    protected $requestedFrequency;
    protected $requestedCount;
    protected $requestedInterval;
    protected $requestedDays;
    protected $requestedMonths;
    protected $requestedStart;
    protected $requestedEnd;
    protected $rRuleString;
    protected $rule;
    public $toText;
    public $occurences;

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

    public function setLang($lang = "en")
    {
        $this->lang = $lang;
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
        $this->rRuleString = rtrim("{$this->requestedCount}{$this->requestedFrequency}{$this->requestedInterval}{$this->requestedDays}{$this->requestedMonths}", ';');
    }

    private function setToText()
    {
        $textTransformer = new TextTransformer(
            new \Recurr\Transformer\Translator($this->lang)
        );

        $this->toText = $textTransformer->transform($this->rule);
    }

    private function setOccurences()
    {
        $transformer = new \Recurr\Transformer\ArrayTransformer();
        $this->occurences = collect($transformer->transform($this->rule));
    }

    /**
     * Create a Rule object
     * @return [type] [description]
     */
    public function build()
    {
        $this->setRuleString();
        $this->rule = new Rule($this->rRuleString, $this->requestedStart, $this->requestedEnd);

        $this->setToText();
        $this->setOccurences();
        return $this;
    }
}
