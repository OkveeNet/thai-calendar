<?php


namespace Rundiz\Calendar\Tests;

class CalendarWeeksInMonthTests extends \PHPUnit\Framework\TestCase
{


    public function testWeeksInMonth28To31DaysAMonth()
    {
        $Calendar = new \Rundiz\Calendar\Calendar();
        // 28 days a month
        $this->assertEquals(4, $Calendar->weeksInMonth('02', '2009', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2009', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2009', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2010', 0));
        $this->assertEquals(4, $Calendar->weeksInMonth('02', '2010', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2010', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2011', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2011', 1));
        $this->assertEquals(4, $Calendar->weeksInMonth('02', '2011', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2006', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2006', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2006', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2007', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2007', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2007', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2013', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2013', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2013', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2014', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2014', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2014', 2));
        // 29 days a month
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2032', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2032', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2032', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2016', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2016', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2016', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2028', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2028', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2028', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2012', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2012', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2012', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2024', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2024', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2024', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2036', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2036', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2036', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2020', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2020', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('02', '2020', 2));
        // 30 days a month
        $this->assertEquals(5, $Calendar->weeksInMonth('11', '2015', 0));
        $this->assertEquals(6, $Calendar->weeksInMonth('11', '2015', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('11', '2015', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('06', '2015', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('06', '2015', 1));
        $this->assertEquals(6, $Calendar->weeksInMonth('06', '2015', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('04', '2014', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('04', '2014', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('04', '2014', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('04', '2015', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('04', '2015', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('04', '2015', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('11', '2012', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('11', '2012', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('11', '2012', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('06', '2012', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('06', '2012', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('06', '2012', 2));

        $this->assertEquals(6, $Calendar->weeksInMonth('06', '2013', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('06', '2013', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('06', '2013', 2));
        // 31 days a month
        $this->assertEquals(5, $Calendar->weeksInMonth('03', '2015', 0));
        $this->assertEquals(6, $Calendar->weeksInMonth('03', '2015', 1));
        $this->assertEquals(6, $Calendar->weeksInMonth('03', '2015', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('12', '2014', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('12', '2014', 1));
        $this->assertEquals(6, $Calendar->weeksInMonth('12', '2014', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('07', '2014', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('07', '2014', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('07', '2014', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('10', '2014', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('10', '2014', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('10', '2014', 2));

        $this->assertEquals(5, $Calendar->weeksInMonth('05', '2014', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('05', '2014', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('05', '2014', 2));

        $this->assertEquals(6, $Calendar->weeksInMonth('03', '2013', 0));
        $this->assertEquals(5, $Calendar->weeksInMonth('03', '2013', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('03', '2013', 2));

        $this->assertEquals(6, $Calendar->weeksInMonth('03', '2014', 0));
        $this->assertEquals(6, $Calendar->weeksInMonth('03', '2014', 1));
        $this->assertEquals(5, $Calendar->weeksInMonth('03', '2014', 2));

        unset($Calendar);
    }// testWeeksInMonth28To31DaysAMonth


    public function testWeeksInMonthYear2014()
    {
        $test_months_years = array(
            // month-year => array(day number => expect result, day number => expect result),
            '01-2014' => array(0 => 5, 1 => 5, 2 => 5),
            '02-2014' => array(0 => 5, 1 => 5, 2 => 5),
            '03-2014' => array(0 => 6, 1 => 6, 2 => 5),
            '04-2014' => array(0 => 5, 1 => 5, 2 => 5),
            '05-2014' => array(0 => 5, 1 => 5, 2 => 5),
            '06-2014' => array(0 => 5, 1 => 6, 2 => 5),
            '07-2014' => array(0 => 5, 1 => 5, 2 => 5),
            '08-2014' => array(0 => 6, 1 => 5, 2 => 5),
            '09-2014' => array(0 => 5, 1 => 5, 2 => 6),
            '10-2014' => array(0 => 5, 1 => 5, 2 => 5),
            '11-2014' => array(0 => 6, 1 => 5, 2 => 5),
            '12-2014' => array(0 => 5, 1 => 5, 2 => 6),
        );

        $Calendar = new \Rundiz\Calendar\Calendar();

        foreach ($test_months_years as $month_year => $days_and_expect_result) {
            list($month, $year) = explode('-', $month_year);
            if (is_array($days_and_expect_result)) {
                foreach ($days_and_expect_result as $day_num => $expect_result) {
                    $this->assertEquals($expect_result, $Calendar->weeksInMonth($month, $year, $day_num));
                }
                unset($day_num, $expect_result);
            } else {
                $this->assertTrue(is_array($days_and_expect_result));
            }
            unset($month, $year);
        }

        unset($Calendar, $days_and_expect_result, $month_year, $test_months_years);
    }// testWeeksInMonthYear2014


    public function testWeeksInMonthYear2016()
    {
        $test_months_years = array(
            // month-year => array(day number => expect result, day number => expect result),
            '01-2016' => array(0 => 6, 1 => 5),
            '02-2016' => array(0 => 5, 1 => 5),
            '03-2016' => array(0 => 5, 1 => 5),
            '04-2016' => array(0 => 5, 1 => 5),
            '05-2016' => array(0 => 5, 1 => 6),
            '06-2016' => array(0 => 5, 1 => 5),
            '07-2016' => array(0 => 6, 1 => 5),
            '08-2016' => array(0 => 5, 1 => 5),
            '09-2016' => array(0 => 5, 1 => 5),
            '10-2016' => array(0 => 6, 1 => 6),
            '11-2016' => array(0 => 5, 1 => 5),
            '12-2016' => array(0 => 5, 1 => 5),
        );

        $Calendar = new \Rundiz\Calendar\Calendar();

        foreach ($test_months_years as $month_year => $days_and_expect_result) {
            list($month, $year) = explode('-', $month_year);
            if (is_array($days_and_expect_result)) {
                foreach ($days_and_expect_result as $day_num => $expect_result) {
                    $this->assertEquals($expect_result, $Calendar->weeksInMonth($month, $year, $day_num));
                }
                unset($day_num, $expect_result);
            } else {
                $this->assertTrue(is_array($days_and_expect_result));
            }
            unset($month, $year);
        }

        unset($Calendar, $days_and_expect_result, $month_year, $test_months_years);
    }// testWeeksInMonthYear2016


}
