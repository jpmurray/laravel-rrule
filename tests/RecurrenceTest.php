<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use jpmurray\LaravelRrule\Recurrence;

class RecurrenceTest extends TestCase
{
    /**
     * @var array
     */
    protected $recurrence;

    /**
     * Construct the box with the given items.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->recurrence = new Recurrence();
    }

    /**
     * Check that we can setFrequency
     *
     * @return void
     */
    public function test_setFrequency()
    {
        $this->recurrence->setFrequency('weekly');
        $expected = "FREQ=WEEKLY;";
        $this->assertEquals($this->recurrence->getFrequency(), $expected);
    }

    /**
     * Check that we can setInterval
     *
     * @return void
     */
    public function test_setInterval()
    {
        $this->recurrence->setInterval(1);
        $expected = "INTERVAL=1;";
        $this->assertEquals($this->recurrence->getInterval(), $expected);
    }

    /**
     * Check that we can set one day, without constraint
     *
     * @return void
     */
    public function test_setDays_with_one_value_no_constraint()
    {
        $this->recurrence->setDays([
            ['sunday', null],
        ]);
        $expected = "BYDAY=SU;";
        $this->assertEquals($this->recurrence->getDays(), $expected);
    }

    /**
     * Check that we can set one day, with constraint
     *
     * @return void
     */
    public function test_setDays_with_one_value_with_constraint()
    {
        $this->recurrence->setDays([
            ['sunday', -1],
        ]);
        $expected = "BYDAY=-1SU;";
        $this->assertEquals($this->recurrence->getDays(), $expected);
    }

    /**
     * Check that we can set multiple day, without constraint
     *
     * @return void
     */
    public function test_setDays_with_multiple_value_no_constraint()
    {
        $this->recurrence->setDays([
            ['sunday', null],
            ['friday', null],
        ]);
        $expected = "BYDAY=SU,FR;";
        $this->assertEquals($this->recurrence->getDays(), $expected);
    }

    /**
     * Check that we can set multiple day, with constraint
     *
     * @return void
     */
    public function test_setDays_with_multiple_value_with_constraint()
    {
        $this->recurrence->setDays([
            ['sunday', -1],
            ['friday', null],
        ]);
        $expected = "BYDAY=-1SU,FR;";
        $this->assertEquals($this->recurrence->getDays(), $expected);
    }

    /**
     * Check that we can set one month
     *
     * @return void
     */
    public function test_setMonths_with_one_value()
    {
        $this->recurrence->setMonths(['january']);
        $expected = "BYMONTH=1;";
        $this->assertEquals($this->recurrence->getMonths(), $expected);
    }

    /**
     * Check that we can set multiple month
     *
     * @return void
     */
    public function test_setMonths_with_multiple_value()
    {
        $this->recurrence->setMonths(['january', 'october', 'august']);
        $expected = "BYMONTH=1,10,8;";
        $this->assertEquals($this->recurrence->getMonths(), $expected);
    }

    /**
     * Check that we can set a start date for occurence
     *
     * @return void
     */
    public function test_setStart()
    {
        $now = Carbon::now();
        $this->recurrence->setStart($now);
        $this->assertEquals($this->recurrence->getStart(), $now);
    }

    /**
     * Check that we can set a end date for occurence
     *
     * @return void
     */
    public function test_setEnd()
    {
        $now = Carbon::now();
        $this->recurrence->setEnd($now);
        $this->assertEquals($this->recurrence->getEnd(), $now);
    }

    /**
     * Check that we can properly generate the string to use in Rrule
     *
     * @return void
     */
    public function test_string_generation_for_rrule()
    {
        $expected = "COUNT=1;FREQ=WEEKLY;INTERVAL=1;BYDAY=MO";
        $this->recurrence->setCount(1)->setFrequency('weekly')->setInterval(1)->setDays([['monday', null]]);
        $this->assertEquals($this->recurrence->getRruleString(), $expected);
    }

    public function test_occurence_generation()
    {
        $this->recurrence->setCount(5)->setFrequency('weekly')->setInterval(1)->setDays([['monday', null]]);

        $this->assertCount(5, $this->recurrence->getOccurences());
        foreach ($this->recurrence->getOccurences() as $key => $occurence) {
            $this->assertInternalType('object', $occurence);

            foreach ($occurence as $key => $value) {
                $this->assertInstanceOf(Carbon::class, $value);
            }
        }
    }

    /**
     * Check that we can properly generate an english readable string of the rules
     *
     * @return void
     */
    public function test_getToText()
    {
        $this->recurrence->setCount(1)->setFrequency('weekly')->setInterval(1)->setDays([['monday', null]]);
        
        $expected = "weekly on Monday once";
        $this->assertEquals($this->recurrence->getToText(), $expected);
    }

    /**
     * Check that we can properly generate an english readable string of the rules, in french
     *
     * @return void
     */
    public function test_getToText_fr()
    {
        $this->recurrence->setCount(1)->setFrequency('weekly')->setInterval(1)->setDays([['monday', null]])->setLang('fr');

        $expected = "chaque semaine le lundi une fois";
        $this->assertEquals($this->recurrence->getToText(), $expected);
    }

    /**
     * Check that we can set a date to start generation of occurences from
     *
     * @return void
     */
    public function test_setFrom()
    {
        $now = Carbon::now();
        $this->recurrence->setFrom($now);
        $expected = "DTSTART={$now->format('Ymd')};";
    
        $this->assertEquals($this->recurrence->getFrom(), $expected);
    }

    /**
     * Check that we can set a date to limit the generation of occurences
     *
     * @return void
     */
    public function test_setUntil()
    {
        $now = Carbon::now();
        $this->recurrence->setUntil($now);
        $expected = "UNTIL={$now->format('Ymd')};";
    
        $this->assertEquals($this->recurrence->getUntil(), $expected);
    }

    /**
     * Check that default language is set to 'en'
     *
     * @return void
     */
    public function test_set_language()
    {
        $this->recurrence->setLang('fr');
        $expected = "fr";
        $this->assertEquals($this->recurrence->getLang(), $expected);
    }

    /**
     * Check that default language is set to 'en'
     *
     * @return void
     */
    public function test_default_language()
    {
        $expected = "en";
        $this->assertEquals($this->recurrence->getLang(), $expected);
    }

    /**
     * Check that we can setCount
     *
     * @return void
     */
    public function test_setCount()
    {
        $this->recurrence->setCount(1);
        $expected = "COUNT=1;";
        $this->assertEquals($this->recurrence->getCount(), $expected);
    }

    /**
     * Check of the rRuleFrequencies attribute exists
     *
     * @return void
     */
    public function test_if_rRuleFrequencies_attribute_exists()
    {
        $this->assertClassHasAttribute('rRuleFrequencies', Recurrence::class);
    }

    /**
     * Check of the rRuleByDay attribute exists
     *
     * @return void
     */
    public function test_if_rRuleByDay_attribute_exists()
    {
        $this->assertClassHasAttribute('rRuleByDay', Recurrence::class);
    }

    /**
     * Check of the rRuleByMonth attribute exists
     *
     * @return void
     */
    public function test_if_rRuleByMonth_attribute_exists()
    {
        $this->assertClassHasAttribute('rRuleByMonth', Recurrence::class);
    }
}
