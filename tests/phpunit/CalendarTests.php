<?php


namespace Rundiz\Calendar\Tests;

class CalendarTests extends \PHPUnit_Framework_TestCase
{


    public $Calendar;
    public $events;


    public function setUp()
    {
        $this->Calendar = new \Rundiz\Calendar\Calendar();
        $this->Calendar->viewing_date = date('Y-m-d');

        $date = new \DateTime(date('Y-m-d'));
        $date->add(new \DateInterval('P1D'));
        $tomorrow = $date->format('Y-m-d');
        $date->add(new \DateInterval('P1D'));
        $after_tomorrow = $date->format('Y-m-d');
        unset($date);

        $this->events = array(
            array(
                'date_from' => date('Y-m-d 00:00:00'),
                'date_to' => date('Y-m-d 00:30:00'),
                'title' => 'Event from 00:00 to 00:30',
            ),
            array(
                'date_from' => date('Y-m-d 00:30:00'),
                'date_to' => date('Y-m-d 01:00:00'),
                'title' => 'Event from 00:30 to 01:00',
            ),
            array(
                'date_from' => date('Y-m-d 00:00:00'),
                'date_to' => date('Y-m-d 00:45:00'),
                'title' => 'Event from 00:00 to 00:45',
            ),
            array(
                'date_from' => date('Y-m-d 00:20:00'),
                'date_to' => date('Y-m-d 00:50:00'),
                'title' => 'Event from 00:20 to 00:50',
            ),
            array(
                'date_from' => date('Y-m-d 11:10:00'),
                'date_to' => date('Y-m-d 12:30:00'),
                'title' => 'Event from 11:10 to 12:30',
            ),
            array(
                'date_from' => date('Y-m-d 13:30:00'),
                'date_to' => date('Y-m-d 14:00:00'),
                'title' => 'Event from 13:30 to 14:00',
            ),
            array(
                'date_from' => date('Y-m-d 13:30:00'),
                'date_to' => date('Y-m-d 14:00:00'),
                'title' => 'Event 2 (duplicated) from 13:30 to 14:00',
            ),
            array(
                'date_from' => date('Y-m-d 23:30:00'),
                'date_to' => $tomorrow.' 00:00:00',
                'title' => 'Event from 23:30 to 00:00 tomorrow',
            ),
            array(
                'date_from' => $after_tomorrow.' 00:20:00',
                'date_to' => $after_tomorrow.' 00:50:00',
                'title' => 'Event after tomorrow from 00:20 to 00:50',
            ),
            array(
                'date_from' => $after_tomorrow.' 11:10:00',
                'date_to' => $after_tomorrow.' 12:30:00',
                'title' => 'Event after tomorrow from 11:10 to 12:30',
            ),
        );

        unset($after_tomorrow, $tomorrow);
    }// setUp


    public function tearDown()
    {
        $this->Calendar->clear();
        $this->events = null;
        $this->Calendar = null;
    }// tearDown


    public function testCalendarScopeDay()
    {
        $this->assertEquals(2, count($this->Calendar->getCalendarData('day')));
        $this->assertEquals(63, count($this->Calendar->getCalendarData('day'), COUNT_RECURSIVE));
        $this->Calendar->setEvents($this->events);
        $this->assertEquals(111, count($this->Calendar->getCalendarData('day'), COUNT_RECURSIVE));
        $this->Calendar->setEvents(array());
    }// testCalendarScopeDay


    public function testCalendarScopeWeek()
    {
        $this->assertEquals(2, count($this->Calendar->getCalendarData('week')));
        $this->assertEquals(365, count($this->Calendar->getCalendarData('week'), COUNT_RECURSIVE));
        $this->Calendar->setEvents($this->events);
        $this->assertEquals(433, count($this->Calendar->getCalendarData('week'), COUNT_RECURSIVE));
        $this->Calendar->setEvents(array());
    }// testCalendarScopeWeek


    public function testCalendarScopeMonth()
    {
        $this->assertEquals(2, count($this->Calendar->getCalendarData('month')));
        $this->assertEquals(156, count($this->Calendar->getCalendarData('month'), COUNT_RECURSIVE));
        $this->Calendar->setEvents($this->events);
        $this->assertEquals(200, count($this->Calendar->getCalendarData('month'), COUNT_RECURSIVE));
        $this->Calendar->setEvents(array());
    }// testCalendarScopeMonth


    public function testCalendarScopeYear()
    {
        $this->assertEquals(2, count($this->Calendar->getCalendarData('year')));
        $this->assertEquals(1806, count($this->Calendar->getCalendarData('year'), COUNT_RECURSIVE));
        $this->Calendar->setEvents($this->events);
        $this->assertEquals(1850, count($this->Calendar->getCalendarData('year'), COUNT_RECURSIVE));
        $this->Calendar->setEvents(array());
    }// testCalendarScopeYear


}
