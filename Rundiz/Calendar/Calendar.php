<?php
/**
 * Rundiz Calendar component.
 * 
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rundiz\Calendar;

/**
 * Construct the calendar data and render calendar HTML by using template.
 *
 * @package Calendar
 * @version 2.0
 * @author Vee W.
 */
class Calendar
{


    /**
     * @var sting Base URL for use with link to navigate the date.
     */
    public $base_url;
    /**
     * @var string Set the currently viewing date. <br>
     * Example: 2016-04-28, 2016-04, 2016. <br>
     * Where first example required for scope day & week but you can use it for all scope, <br>
     * second example required for scope month but you can use it with year scope, <br>
     * third example required for scope year.
     */
    public $viewing_date;
    /**
     * @var string The original value of viewing_date property before it was changed. This is useful in the class while using loop to set viewing date and get the calendar's data.
     */
    protected $original_viewing_date;
    /**
     * @var integer First day of week. (0 = Sunday, 1 = Monday, 2 = Tuesday, ..., 6 = Saturday)
     */
    public $first_day_of_week = 0;
    /**
     * @var mixed Locale for use with setlocale() function. See more at http://php.net/manual/en/function.setlocale.php
     */
    public $locale = array('th_TH.utf8', 'th_TH.UTF8', 'th_TH.utf-8', 'th_TH.UTF-8', 'th-TH.utf8', 'th-TH.UTF8', 'th-TH.utf-8', 'th-TH.UTF-8', 'th_TH', 'th-TH', 'th');
    /**
     * @var constant Locale category. See more at http://php.net/manual/en/function.setlocale.php
     */
    public $locale_category = LC_TIME;
    /**
     * @var boolean Use Buddhist era or not? Set to true to use Buddhist era, false not to use.
     */
    public $use_buddhist_era = true;
    /**
     * @var integer Buddhist era year offset. For Thailand we use +543.
     */
    public $buddhist_era_offset = 543;
    /**
     * @var integer Buddhist era year offset (in short or 2 digit number). For Thailand we use +43.
     */
    public $buddhist_era_offset_short = 43;

    /**
     * @var array The events that will be appears in calendar, or it can be appointments.
     */
    protected $events;


    /**
     * Push the matched array key to the first and place those shifed to the end of array.
     * 
     * @param array $array Array item such as day number.
     * @param integer $first_key Number of key that will be first. This is count from 0 even the array key is not number.
     * @return array Return the pushed array.
     */
    private function arrayCarousel(array $array, $first_key = 0) {
        if ($first_key === 0) {
            return $array;
        }

        $hold_array = array();
        $ikey = 0;
        foreach ($array as $key => $item) {
            if ($ikey == $first_key) {
                break;
            }

            $hold_array = array_merge($hold_array, array($key => $item));
            unset($array[$key]);

            $ikey++;
        }
        unset($ikey);

        return $array + $hold_array;
    }// arrayCarousel


    /**
     * Clear and reset the properties to all default values.
     */
    public function clear()
    {
        $this->locale = array('th', 'th_TH.utf8', 'th_TH.UTF8', 'th_TH.utf-8', 'th_TH.UTF-8', 'th_TH', 'th-TH');
        $this->locale_category = LC_TIME;
        $this->use_buddhist_era = true;
        $this->buddhist_era_offset = 543;
        $this->buddhist_era_offset_short = 43;
        $this->viewing_date = null;
        $this->original_viewing_date = null;
        $this->first_day_of_week = 0;

        $this->events = null;
    }// clear


    /**
     * Display the calendar.
     * 
     * @param string $scope Scope of the calendar you want to display. (day, week, month, year)
     * @param string $Generator The generator class for generate calendar HTML.
     * @return string Return generated calendar HTML.
     */
    public function display($scope = 'month', $Generator = '\\Rundiz\\Calendar\\Generators\\Simple')
    {
        if ($Generator == null) {
            $Generator = '\\Rundiz\\Calendar\\Generators\\Simple';
        }

        $calendar_data = $this->getCalendarData($scope);

        $Generator = new $Generator($calendar_data);
        $Generator->base_url = $this->base_url;
        $Generator->use_buddhist_era = $this->use_buddhist_era;
        $Generator->buddhist_era_offset = $this->buddhist_era_offset;
        $Generator->buddhist_era_offset_short = $this->buddhist_era_offset_short;
        return $Generator->display($scope);
    }// display


    /**
     * Get the calendar data with specified scope.<br>
     * This method is not required if you use display() method.
     * 
     * @param string $scope Scope of the calendar you want to display. (day, week, month, year)
     * @return array Return generated calendar data as array for loop displaying the calendar.
     */
    public function getCalendarData($scope = 'month')
    {
        $scope = $this->validateAndGetCorrectScope($scope);
        $this->validateViewingDate($scope);
        $this->validateFirstDayOfWeek();
        $this->setupLocale();

        switch ($scope) {
            case 'year':
                return $this->getCalendarScopeYear();
            case 'month':
                return $this->getCalendarScopeMonth();
            case 'week':
                return $this->getCalendarScopeWeek();
            case 'day':
                return $this->getCalendarDataScopeDay();
            default:
                break;
        }
    }// getCalendarData


    /**
     * Get the calendar data with scope day.
     * 
     * @return array Return generated calendar data as array for loop displaying the calendar.
     */
    private function getCalendarDataScopeDay()
    {
        $output = array();

        $viewing_date = $this->getViewingDateOutputData();
        if (is_array($viewing_date) && array_key_exists('viewing_date', $viewing_date)) {
            $output['viewing_date'] = $viewing_date['viewing_date'];
        }
        unset($viewing_date);

        $output['calendar'] = array();
        $output['calendar']['times'] = array();

        for ($ihour = 0; $ihour <= 23; $ihour++) {
            for ($iminute = 0; $iminute <= 30; $iminute+= 30) {
                $this_loop_hour_minute = str_pad($ihour, 2, '0', STR_PAD_LEFT).':'.str_pad($iminute, 2, '0', STR_PAD_LEFT);
                $this_loop_datetime = $output['viewing_date']['full_date'].' '.$this_loop_hour_minute;
                $date = new \DateTime($this_loop_datetime);
                $this_loop_datetime = $date->format('Y-m-d H:i:s');
                $date->add(new \DateInterval('PT30M'));
                $this_loop_datetime_next30min = $date->format('Y-m-d H:i:s');
                unset($date);

                $output['calendar']['times'][$this_loop_hour_minute] = array();

                if (is_array($this->events) && !empty($this->events)) {
                    foreach ($this->events as $event_key => $event) {
                        if (new \DateTime($event['date_from']) >= new \DateTime($this_loop_datetime) && new \DateTime($event['date_from']) < new \DateTime($this_loop_datetime_next30min)) {
                            // 00:00 >= 00:00 && 00:00 < 00:30 (00:00 - 00:30)
                            // 00:00 >= 00:00 && 00:00 < 00:30 (00:00 - 00:45)
                            // 00:30 >= 00:30 && 00:30 < 01:00 (00:30 - 01:00)
                            // 11:10 >= 11:00 && 11:10 < 11:30 (11:10 - 12:30)
                            // 11:10 >= 12:00 && 11:10 < 12:30 (11:10 - 12:30)
                            $output['calendar']['times'][$this_loop_hour_minute] = $output['calendar']['times'][$this_loop_hour_minute] + array($event_key => $event);
                        } elseif (new \DateTime($event['date_to']) > new \DateTime($this_loop_datetime) && new \DateTime($event['date_to']) < new \DateTime($this_loop_datetime_next30min)) {
                            // 00:45 > 00:30 && 00:45 < 01:00 (00:00 - 00:45)
                            $output['calendar']['times'][$this_loop_hour_minute] = $output['calendar']['times'][$this_loop_hour_minute] + array($event_key => $event);
                        } elseif (new \DateTime($event['date_from']) < new \DateTime($this_loop_datetime) && new \DateTime($event['date_to']) >= new \DateTime($this_loop_datetime_next30min)) {
                            // 11:10 < 11:30 && 12:30 >= 12:00 (11:10 - 12:30) 
                            $output['calendar']['times'][$this_loop_hour_minute] = $output['calendar']['times'][$this_loop_hour_minute] + array($event_key => $event);
                        }
                    }// endforeach;
                    unset($event, $event_key);
                }

                unset($this_loop_datetime, $this_loop_hour_minute);
            }// endfor;
            unset($iminute);
        }// endfor;
        unset($ihour);

        return $output;
    }// getCalendarScopeDay


    /**
     * Get the calendar with scope month.
     * 
     * @return array Return generated calendar data as array for loop displaying the calendar.
     */
    private function getCalendarScopeMonth()
    {
        $output = array();

        $viewing_date = $this->getViewingDateOutputData();
        if (is_array($viewing_date) && array_key_exists('viewing_date', $viewing_date)) {
            $output['viewing_date'] = $viewing_date['viewing_date'];
        }
        unset($viewing_date);

        $output['calendar'] = array();

        // set the year navigation.
        $output['calendar']['year_navigation'] = array();
        $date = new \DateTime($output['viewing_date']['full_date']);
        $date->sub(new \DateInterval('P1Y'));
        $output['calendar']['year_navigation']['previous'] = $date->format('Y');
        $output['calendar']['year_navigation']['previous_buddhist_era_full'] = ($date->format('Y') + $this->buddhist_era_offset);
        $output['calendar']['year_navigation']['previous_buddhist_era_short'] = ($date->format('y') + $this->buddhist_era_offset_short);
        $date->add(new \DateInterval('P1Y'));
        $output['calendar']['year_navigation']['current'] = $date->format('Y');
        $output['calendar']['year_navigation']['current_buddhist_era_full'] = ($date->format('Y') + $this->buddhist_era_offset);
        $output['calendar']['year_navigation']['current_buddhist_era_short'] = ($date->format('y') + $this->buddhist_era_offset_short);
        $date->add(new \DateInterval('P1Y'));
        $output['calendar']['year_navigation']['next'] = $date->format('Y');
        $output['calendar']['year_navigation']['next_buddhist_era_full'] = ($date->format('Y') + $this->buddhist_era_offset);
        $output['calendar']['year_navigation']['next_buddhist_era_short'] = ($date->format('y') + $this->buddhist_era_offset_short);
        unset($date);

        // detect encoding
        $example_encoding = strftime('%a', mktime(0, 0, 0, $output['viewing_date']['month'], $output['viewing_date']['date'], $output['viewing_date']['year']));
        $detect_encoding = mb_detect_encoding($example_encoding, mb_detect_order(), true);
        unset($example_encoding);

        // set the month navigation
        $output['calendar']['month_navigation'] = array();
        $date = new \DateTime($output['viewing_date']['full_date']);
        $date->sub(new \DateInterval('P1M'));
        $output['calendar']['month_navigation']['previous'] = $date->format('Y-m');
        $output['calendar']['month_navigation']['previous_month_number'] = $date->format('m');
        $output['calendar']['month_navigation']['previous_month_name_short'] = iconv($detect_encoding, 'UTF-8', strftime('%b', $date->getTimestamp()));
        $output['calendar']['month_navigation']['previous_month_name_full'] = iconv($detect_encoding, 'UTF-8', strftime('%B', $date->getTimestamp()));
        $output['calendar']['month_navigation']['previous_month_year_full'] = $date->format('Y');
        $output['calendar']['month_navigation']['previous_month_year_short'] = $date->format('y');
        $output['calendar']['month_navigation']['previous_month_year_buddhist_era_full'] = ($date->format('Y') + $this->buddhist_era_offset);
        $output['calendar']['month_navigation']['previous_month_year_buddhist_era_short'] = ($date->format('y') + $this->buddhist_era_offset_short);
        $date->add(new \DateInterval('P1M'));
        $output['calendar']['month_navigation']['current'] = $date->format('Y-m');
        $output['calendar']['month_navigation']['current_month_number'] = $date->format('m');
        $output['calendar']['month_navigation']['current_month_name_short'] = iconv($detect_encoding, 'UTF-8', strftime('%b', $date->getTimestamp()));
        $output['calendar']['month_navigation']['current_month_name_full'] = iconv($detect_encoding, 'UTF-8', strftime('%B', $date->getTimestamp()));
        $output['calendar']['month_navigation']['current_month_year_full'] = $date->format('Y');
        $output['calendar']['month_navigation']['current_month_year_short'] = $date->format('y');
        $output['calendar']['month_navigation']['current_month_year_buddhist_era_full'] = ($date->format('Y') + $this->buddhist_era_offset);
        $output['calendar']['month_navigation']['current_month_year_buddhist_era_short'] = ($date->format('y') + $this->buddhist_era_offset_short);
        $date->add(new \DateInterval('P1M'));
        $output['calendar']['month_navigation']['next'] = $date->format('Y-m');
        $output['calendar']['month_navigation']['next_month_number'] = $date->format('m');
        $output['calendar']['month_navigation']['next_month_name_short'] = iconv($detect_encoding, 'UTF-8', strftime('%b', $date->getTimestamp()));
        $output['calendar']['month_navigation']['next_month_name_full'] = iconv($detect_encoding, 'UTF-8', strftime('%B', $date->getTimestamp()));
        $output['calendar']['month_navigation']['next_month_year_full'] = $date->format('Y');
        $output['calendar']['month_navigation']['next_month_year_short'] = $date->format('y');
        $output['calendar']['month_navigation']['next_month_year_buddhist_era_full'] = ($date->format('Y') + $this->buddhist_era_offset);
        $output['calendar']['month_navigation']['next_month_year_buddhist_era_short'] = ($date->format('y') + $this->buddhist_era_offset_short);
        unset($date);

        // set the days heading
        $output['calendar']['days_heading'] = array();
        // get day number of viewing date (0=Sunday, 6=Saturday)
        $day_number = date('w', strtotime($output['viewing_date']['full_date']));
        // get the first date of this selected week by first day is sunday.
        $date = new \DateTime($output['viewing_date']['full_date']);
        $date->sub(new \DateInterval('P'.$day_number.'D'));
        unset($day_number);
        // setup dates in this viewing week.
        for ($i = 0; $i <= 6; $i++) {
            $output['calendar']['days_heading'][$i]['day_short'] = iconv($detect_encoding, 'UTF-8', strftime('%a', $date->getTimestamp()));
            $output['calendar']['days_heading'][$i]['day_full'] = iconv($detect_encoding, 'UTF-8', strftime('%A', $date->getTimestamp()));
            $date->add(new \DateInterval('P1D'));
        }// endfor;
        unset($date, $detect_encoding, $i);
        $output['calendar']['days_heading'] = $this->arrayCarousel($output['calendar']['days_heading'], $this->first_day_of_week);

        // loop set month calendar
        $total_weeks = $this->weeksInMonth($output['viewing_date']['month'], $output['viewing_date']['year'], $this->first_day_of_week);
        $date = new \DateTime($output['viewing_date']['full_date']);
        $total_dates_in_month = $date->format('t');
        unset($date);
        $output['calendar']['weeks'] = array();
        $idate = 1;
        $ilastdate = 1;
        $idate_beforemonth = 0;
        // working date and working full date is the date currently in the loop.
        $working_date = null;// example: 14
        $working_full_date = null;// example: 2016-05-14
        for ($iweek = 1; $iweek <= $total_weeks; $iweek++) {
            $output['calendar']['weeks'][$iweek] = array();
            $output['calendar']['weeks'][$iweek]['dates'] = array();
            if (is_array($output['calendar']['days_heading'])) {
                foreach ($output['calendar']['days_heading'] as $day_key => $day_number) {
                    $output['calendar']['weeks'][$iweek]['dates'][$day_key] = array();
                    if (checkdate($output['viewing_date']['month'], $idate, $output['viewing_date']['year'])) {
                        // checked date and it's valid. (Example: 2016-13-32 is not valid, 2016-12-31 is valid).
                        if (date('w', strtotime($output['viewing_date']['year'].'-'.$output['viewing_date']['month'].'-'.$idate)) == $day_key) {
                            $working_date = str_pad($idate, 2, '0', STR_PAD_LEFT);
                            $working_full_date = $output['viewing_date']['year'].'-'.$output['viewing_date']['month'].'-'.str_pad($idate, 2, '0', STR_PAD_LEFT);
                            $output['calendar']['weeks'][$iweek]['dates'][$day_key][str_pad($idate, 2, '0', STR_PAD_LEFT)] = array();
                            $idate++;
                        } else {
                            // dates is before this month.
                            $first_date_day_number = date('w', strtotime($output['viewing_date']['year'].'-'.$output['viewing_date']['month'].'-01'));// 0=Sunday, 6=Saturday
                            $difference_dates = $first_date_day_number - $this->first_day_of_week;
                            if ($difference_dates < 0) {
                               $difference_dates = (7 + ($first_date_day_number - $this->first_day_of_week));
                            }
                            //$difference_dates = (($day_key >= 6 ? -1 : $day_key) - date('w', strtotime($output['viewing_date']['year'].'-'.$output['viewing_date']['month'].'-01')));
                            $date = new \DateTime($output['viewing_date']['year'].'-'.$output['viewing_date']['month'].'-'.$idate);
                            $date->modify('-'.($difference_dates + $idate_beforemonth).'days');
                            $output['calendar']['weeks'][$iweek]['dates'][$day_key][strval($date->format('d'))] = array();
                            $output['calendar']['weeks'][$iweek]['dates'][$day_key]['dates-before-month'] = true;
                            $working_date = strval($date->format('d'));
                            $working_full_date = strval($date->format('Y-m-d'));
                            $idate_beforemonth--;
                            unset($date, $difference_dates);
                        }
                    } else {
                        // dates is after this month.
                        $date = new \DateTime($output['viewing_date']['year'].'-'.$output['viewing_date']['month'].'-'.$total_dates_in_month);
                        $date->add(new \DateInterval('P'.$ilastdate.'D'));
                        $ilastdate++;
                        $output['calendar']['weeks'][$iweek]['dates'][$day_key][strval($date->format('d'))] = array();
                        $output['calendar']['weeks'][$iweek]['dates'][$day_key]['dates-after-month'] = true;
                        $working_date = strval($date->format('d'));
                        $working_full_date = strval($date->format('Y-m-d'));
                        unset($date);
                    }

                    if (is_array($this->events) && !empty($this->events) && isset($working_date) && $working_date != null && isset($working_full_date) && $working_full_date != null) {
                        foreach ($this->events as $event_key => $event) {
                            $date_event_from = new \DateTime($event['date_from']);
                            $date_event_from->setTime(0, 0, 0);
                            $date_event_to = new \DateTime($event['date_to']);
                            $date_event_to->setTime(0, 0, 0);
                            $date_working_date = new \DateTime($working_full_date);
                            $date_working_date->setTime(0, 0, 0);
                            $date_working_date_tomorrow = new \DateTime($working_full_date);
                            $date_working_date_tomorrow->add(new \DateInterval('P1D'));
                            if ($date_event_from >= $date_working_date && $date_event_from < $date_working_date_tomorrow) {
                                // 2016-05-01 >= 2016-05-01 && 2016-05-01 < 2016-05-02 (2016-05-01 - 2016-05-03) (2016-05-01 - 2016-05-02)
                                // 2016-05-02 >= 2016-05-02 && 2016-05-02 < 2016-05-03 (2016-05-02 - 2016-05-03)
                                // 2016-05-02 >= 2016-05-02 && 2016-05-02 < 2016-05-03 (2016-05-02 - 2016-05-02)
                                // 2016-05-03 >= 2016-05-03 && 2016-05-03 < 2016-05-01 (2016-05-03 - 2016-05-03)
                                $output['calendar']['weeks'][$iweek]['dates'][$day_key][$working_date] = $output['calendar']['weeks'][$iweek]['dates'][$day_key][$working_date] + array($event_key => $event);
                            } elseif ($date_event_to >= $date_working_date && $date_event_to < $date_working_date_tomorrow) {
                                // 2016-05-03 >= 2016-05-02 && 2016-05-03 < 2016-05-04 (2016-05-01 - 2016-05-03) (2016-05-02 - 2016-05-03)
                                $output['calendar']['weeks'][$iweek]['dates'][$day_key][$working_date] = $output['calendar']['weeks'][$iweek]['dates'][$day_key][$working_date] + array($event_key => $event);
                            } elseif ($date_event_from < $date_working_date && $date_event_to >= $date_working_date_tomorrow) {
                                // 2016-05-01 < 2016-05-02 && 2016-05-02 >= 2016-05-03 (2016-05-01 - 2016-05-02)
                                // 2016-05-01 < 2016-05-02 && 2016-05-03 >= 2016-05-03 (2016-05-01 - 2016-05-03)
                                $output['calendar']['weeks'][$iweek]['dates'][$day_key][$working_date] = $output['calendar']['weeks'][$iweek]['dates'][$day_key][$working_date] + array($event_key => $event);
                            }
                            unset($date_event_from, $date_event_to, $date_working_date, $date_working_date_tomorrow);
                        }// endforeach;
                        unset($event, $event_key);
                    }

                    $working_date = null;
                    $working_full_date = null;
                }// endforeach;
                unset($day_number);
            }
        }// endfor;
        unset($idate, $idate_beforemonth, $ilastdate, $iweek, $total_dates_in_month, $total_weeks, $working_date, $working_full_date);

        return $output;
    }// getCalendarScopeMonth


    /**
     * Get the calendar data with scope week.
     * 
     * @return array Return generated calendar data as array for loop displaying the calendar.
     */
    private function getCalendarScopeWeek()
    {
        $output = array();

        $viewing_date = $this->getViewingDateOutputData();
        if (is_array($viewing_date) && array_key_exists('viewing_date', $viewing_date)) {
            $output['viewing_date'] = $viewing_date['viewing_date'];
        }
        unset($viewing_date);

        // get the dates in week.
        $dates_in_viewing_week = $this->getTheDatesInWeek($output['viewing_date']['full_date']);

        $calendar_dates = array();
        if (is_array($dates_in_viewing_week)) {
            foreach ($dates_in_viewing_week as $each_date) {
                if ($this->original_viewing_date == null) {
                    $this->original_viewing_date = $this->viewing_date;
                }
                $this->viewing_date = $each_date;
                $calendar_scope_day = $this->getCalendarDataScopeDay();
                if (is_array($calendar_scope_day)) {
                    unset($calendar_scope_day['viewing_date']);
                    if (array_key_exists('calendar', $calendar_scope_day) && is_array($calendar_scope_day['calendar']) && array_key_exists('times', $calendar_scope_day['calendar']) && is_array($calendar_scope_day['calendar']['times'])) {
                        $calendar_dates['dates'][$each_date]['times'] = $calendar_scope_day['calendar']['times'];
                    }
                }
                unset($calendar_scope_day);
            }// endforeach;
            if ($this->original_viewing_date != null) {
                $this->viewing_date = $this->original_viewing_date;
                $this->original_viewing_date = null;
            }
            unset($each_date);
        }
        $output['calendar'] = $calendar_dates;
        unset($calendar_dates, $dates_in_viewing_week);

        return $output;
    }// getCalendarScopeWeek


    /**
     * Get the calendar with scope year.
     * 
     * @return array Return generated calendar data as array for loop displaying the calendar.
     */
    private function getCalendarScopeYear()
    {
        $output = array();

        $viewing_date = $this->getViewingDateOutputData();
        if (is_array($viewing_date) && array_key_exists('viewing_date', $viewing_date)) {
            $output['viewing_date'] = $viewing_date['viewing_date'];
        }
        unset($viewing_date);

        // loop 12 months to get months calendar data.
        $calendar_months = array();
        for ($imonth = 1; $imonth <= 12; $imonth++) {
            if ($this->original_viewing_date == null) {
                $this->original_viewing_date = $this->viewing_date;
            }
            $this->viewing_date = $output['viewing_date']['year'].'-'.strval(str_pad($imonth, 2, '0', STR_PAD_LEFT)).'-01';
            $calendar_scope_month = $this->getCalendarScopeMonth();
            if (is_array($calendar_scope_month)) {
                unset($calendar_scope_month['viewing_date']);
                if (array_key_exists('calendar', $calendar_scope_month) && is_array($calendar_scope_month['calendar'])) {
                    $calendar_months['months'][strval(str_pad($imonth, 2, '0', STR_PAD_LEFT))] = $calendar_scope_month['calendar'];
                }
            }
        }// endfor;
        if ($this->original_viewing_date != null) {
            $this->viewing_date = $this->original_viewing_date;
            $this->original_viewing_date = null;
        }
        $output['calendar'] = $calendar_months;
        unset($calendar_months, $imonth);

        return $output;
    }// getCalendarScopeYear


    /**
     * Get the dates in a week.
     * 
     * @param string $viewing_date The date that is viewing or current date.
     * @return array Return array of dates in viewing week.
     */
    public function getTheDatesInWeek($viewing_date = null)
    {
        if ($viewing_date == null) {
            $viewing_date = $this->viewing_date;
        }

        $day_number = date('w', strtotime($viewing_date));
        $day_diff = ($day_number - $this->first_day_of_week);
        $date = new \DateTime($viewing_date);
        if ($day_diff < 0) {
            // first day to list is before first day of week.
            $date->add(new \DateInterval('P'.abs($day_diff).'D'));
            $date->sub(new \DateInterval('P7D'));
        } else {
            $date->sub(new \DateInterval('P'.$day_diff.'D'));
        }
        unset($day_diff, $day_number);

        $dates_in_viewing_week = array();
        for ($i = 1; $i <= 7; $i++) {
            $dates_in_viewing_week[] = $date->format('Y-m-d');
            $date->add(new \DateInterval('P1D'));
        }
        unset($date, $i);
        return $dates_in_viewing_week;
    }// getTheDatesInWeek


    /**
     * Get the viewing date for generate output data.<br>
     * This method can be commonly use with get calendar data scope on day, week, month, year.
     * 
     * @return array Return generated viewing date data for send to output or can be use with display method.
     */
    private function getViewingDateOutputData()
    {
        $output = array();
        $output['viewing_date'] = array();
        $output['viewing_date']['full_date'] = $this->viewing_date;

        $output['viewing_date']['year'] = $this->getViewingDatePart('year');
        $output['viewing_date']['month'] = $this->getViewingDatePart('month');
        if ($output['viewing_date']['month'] === false) {
            $output['viewing_date']['month'] = date('m');
        }
        $output['viewing_date']['date'] = $this->getViewingDatePart('date');
        if ($output['viewing_date']['date'] === false) {
            $output['viewing_date']['date'] = 1;
        }

        $viewing_day = strftime('%a', mktime(0, 0, 0, $output['viewing_date']['month'], $output['viewing_date']['date'], $output['viewing_date']['year']));
        $detect_encoding = mb_detect_encoding($viewing_day, mb_detect_order(), true);
        $output['viewing_date']['day_short'] = iconv($detect_encoding, 'UTF-8', $viewing_day);
        $output['viewing_date']['day_full'] = iconv($detect_encoding, 'UTF-8', strftime('%A', mktime(0, 0, 0, $output['viewing_date']['month'], $output['viewing_date']['date'], $output['viewing_date']['year'])));
        unset($viewing_day);

        $output['viewing_date']['month_name_short'] = iconv($detect_encoding, 'UTF-8', strftime('%b', mktime(0, 0, 0, $output['viewing_date']['month'], $output['viewing_date']['date'], $output['viewing_date']['year'])));
        $output['viewing_date']['month_name_full'] = iconv($detect_encoding, 'UTF-8', strftime('%B', mktime(0, 0, 0, $output['viewing_date']['month'], $output['viewing_date']['date'], $output['viewing_date']['year'])));

        $output['viewing_date']['year_short'] = iconv($detect_encoding, 'UTF-8', strftime('%y', mktime(0, 0, 0, $output['viewing_date']['month'], $output['viewing_date']['date'], $output['viewing_date']['year'])));
        $output['viewing_date']['year_full'] = iconv($detect_encoding, 'UTF-8', strftime('%Y', mktime(0, 0, 0, $output['viewing_date']['month'], $output['viewing_date']['date'], $output['viewing_date']['year'])));
        $output['viewing_date']['year_buddhist_era_short'] = ($this->use_buddhist_era === true ? $output['viewing_date']['year_short'] + ($this->buddhist_era_offset_short) : '');
        $output['viewing_date']['year_buddhist_era_full'] = ($this->use_buddhist_era === true ? $output['viewing_date']['year_full'] + $this->buddhist_era_offset : '');
        unset($detect_encoding);

        return $output;
    }// getViewingDateOutputData


    /**
     * Get the part of viewing date.
     * 
     * @param string $data Set the data part to get. (date, month, year)
     * @return mixed Return number of date, month, year if found the data. Return false if failed to get data.
     */
    private function getViewingDatePart($data = 'date')
    {
        $data = strtolower($data);
        if (!in_array($data, array('date', 'month', 'year'))) {
            $data = 'date';
        }

        $viewing_date_exp = explode('-', $this->viewing_date);
        if (!is_array($viewing_date_exp)) {
            return false;
        }

        if ($data == 'date' && isset($viewing_date_exp[2])) {
            return $viewing_date_exp[2];
        } elseif ($data == 'month' && isset($viewing_date_exp[1])) {
            return $viewing_date_exp[1];
        } elseif ($data == 'year' && isset($viewing_date_exp[0])) {
            return $viewing_date_exp[0];
        }
        return false;
    }// getViewingDatePart


    /**
     * Set the events to display in calendar.<br>
     * The format of events must be array with date_from and date_to array key in each event.<br>
     * Example:
     * <pre>
     * $events = array(
     *     array(
     *         'date_from' => '2016-04-28 09:00:00',// Required array key
     *         'date_to' => '2016-04-28 09:40:00',// Required array key
     *         'title' => 'My event 1',// Not required but up to your calendar design.
     *     ),
     *     array(
     *         'date_from' => '2016-04-28 15:20:00',// Required array key
     *         'date_to' => '2016-04-28 15:50:00',// Required array key
     *         'title' => 'My event 2',// Not required but up to your calendar design.
     *     ),
     * );
     * </pre>
     * The value of date_from and date_to must be Year-Month-Date, the time may not required if you did not display calendar in day scope.
     * 
     * @param array $events The events as array.
     * @return boolean Return true on success, false on failure.
     */
    public function setEvents(array $events)
    {
        if (empty($events)) {
            $this->events = null;
            return true;
        }

        $validated_required_key = false;
        foreach ($events as $event) {
            if (is_array($event)) {
                if (array_key_exists('date_from', $event) && array_key_exists('date_to', $event)) {
                    $validated_required_key = true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        unset($event);

        if ($validated_required_key === true) {
            $this->events = $events;
            return true;
        }
    }// setEvents


    /**
     * Setup the locale
     * 
     * @return mixed Returns the new current locale, or FALSE if the locale functionality is not implemented on your platform, the specified locale does not exist or the category name is invalid.
     */
    private function setupLocale()
    {
        return setlocale($this->locale_category, $this->locale);
    }// setupLocale


    /**
     * Validate and get the correct scope.
     * 
     * @param string $scope Valid scope is one of these. (day, week, month, year)
     * @return string Return valid scope.
     */
    private function validateAndGetCorrectScope($scope)
    {
        $scope = strtolower($scope);
        if (!in_array($scope, array('day', 'week', 'month', 'year'))) {
            $scope = 'month';
        }

        return $scope;
    }// validateAndGetCorrectScope


    /**
     * Validate first day of week. 0 = Sunday, 1 = Monday, ..., 6 = Saturday
     */
    private function validateFirstDayOfWeek()
    {
        $this->first_day_of_week = intval($this->first_day_of_week);
        if ($this->first_day_of_week < 0) {
            $this->first_day_of_week = 0;
        } elseif ($this->first_day_of_week > 6) {
            $this->first_day_of_week = 6;
        }
    }// validateFirstDayOfWeek


    /**
     * Validate the viewing_date property to be correct or set.
     * 
     * @param string $scope Scope of the calendar that will be display/generate. (day, week, month, year) This will be use for validate with viewing date that must be set correctly.
     * @throws \Exception Throw error on set the invalid format date to the scope.
     */
    private function validateViewingDate($scope)
    {
        if ($this->viewing_date == null) {
            $this->viewing_date = date('Y-m-d');
        }

        switch ($scope) {
            case 'year':
                // YYYY-MM-DD
                $validate_result = boolval(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$this->viewing_date));
                if ($validate_result !== true) {
                    // YYYY-MM
                    $validate_result = boolval(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/",$this->viewing_date));
                    if ($validate_result !== true) {
                        // YYYY
                        $validate_result = boolval(preg_match("/^[0-9]{4}$/",$this->viewing_date));
                    }
                }
                break;
            case 'month':
                // YYYY-MM-DD
                $validate_result = boolval(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$this->viewing_date));
                if ($validate_result !== true) {
                    // YYYY-MM
                    $validate_result = boolval(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/",$this->viewing_date));
                }
                break;
            case 'week':
                // YYYY-MM-DD
                $validate_result = boolval(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$this->viewing_date));
                break;
            case 'day':
                // YYYY-MM-DD
                $validate_result = boolval(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$this->viewing_date));
                break;
            default:
                break;
        }

        if (isset($validate_result) && $validate_result === true) {
            unset($validate_result);
            return true;
        } else {
            throw new \Exception(sprintf('You have set invalid format of the viewing_date property. (%s)', $this->viewing_date));
        }
    }// validateViewingDate


    /**
     * Get total weeks in a month
     *
     * @author Vee W.
     * @link http://rundiz.com/web-resources/%E0%B8%84%E0%B9%89%E0%B8%99%E0%B8%AB%E0%B8%B2%E0%B8%88%E0%B8%B3%E0%B8%99%E0%B8%A7%E0%B8%99%E0%B8%AA%E0%B8%B1%E0%B8%9B%E0%B8%94%E0%B8%B2%E0%B8%AB%E0%B9%8C%E0%B9%83%E0%B8%99%E0%B8%AB%E0%B8%99%E0%B8%B6 Original source.
     * @param string $month Current month in number. For example: 04 for April.
     * @param string $year Current year in number 4 digits. For example: 2016
     * @param integer $first_day_of_week First day of week. (Sunday is 0, Monday is 1, Tuesday is 2, ..., Saturday is 6).
     * @return mixed Return the number of total weeks in this month and year. Return false if failed to work.
     */
    public function weeksInMonth($month, $year, $first_day_of_week = 0)
    {
        if ($first_day_of_week == null || !is_int($first_day_of_week)) {
            $this->validateFirstDayOfWeek();
            $first_day_of_week = $this->first_day_of_week;
        }

        $timestamp_first_day = mktime(0, 0, 0, $month, 1, $year);
        $total_days = date('t', $timestamp_first_day);
        $timestamp_last_day = mktime(0, 0, 0, $month, $total_days, $year);

        // by default the date('w') will got 0(sunday) - 6(saturday)
        $daynum_firstday = (date('w', $timestamp_first_day) - $first_day_of_week);
        $daynum_lastday = (date('w', $timestamp_last_day) - $first_day_of_week);
        if ($daynum_firstday > 7) {
            $daynum_firstday = 0;
        } elseif ($daynum_firstday < 0) {
            $daynum_firstday = (6 - ((-$daynum_firstday) - 1));
        }
        if ($daynum_lastday > 7) {
            $daynum_lastday = 0;
        } elseif ($daynum_lastday < 0) {
           $daynum_lastday = (6 - ((-$daynum_lastday) - 1));
        }
        unset($timestamp_first_day, $timestamp_last_day);

        // find total weeks (total days / 7) 7 means 7 days a week.
        // 31/7 = 4.4285714285714285714285714285714
        // 30/7 = 4.2857142857142857142857142857143
        // 29/7 = 4.1428571428571428571428571428571
        // 28/7 = 4
        // this will always be 4 and 4.xx, just use 4.
        $total_weeks_approximately = 4;

        switch ($total_days) {
            case 28:
                $daynum_firstday_offset = ($daynum_firstday - 1) - 1;
                $difference_of_day_number = ($daynum_lastday - $daynum_firstday_offset);
                if ($daynum_lastday > $daynum_firstday) {
                    // this means fewer the round.
                    $difference_of_day_number = 0;
                }
                $total_weeks = ($total_weeks_approximately + $difference_of_day_number);
                break;
            case 29:
                $daynum_firstday_offset = ($daynum_firstday - 1) - 0;
                $difference_of_day_number = ($daynum_lastday - $daynum_firstday_offset);
                $total_weeks = ($total_weeks_approximately + $difference_of_day_number);
                break;
            case 30:
                $daynum_firstday_offset = ($daynum_firstday - 1) + 1;
                $difference_of_day_number = ($daynum_lastday - $daynum_firstday_offset);
                if ($daynum_lastday < $daynum_firstday) {
                    // this means over the round.
                    $difference_of_day_number = 2;
                }
                $total_weeks = ($total_weeks_approximately + $difference_of_day_number);
                break;
            case 31:
                $daynum_firstday_offset = ($daynum_firstday - 1) + 2;
                $difference_of_day_number = ($daynum_lastday - $daynum_firstday_offset);
                if ($daynum_lastday < $daynum_firstday) {
                    // this means over the round.
                    $difference_of_day_number = 2;
                }
                $total_weeks = ($total_weeks_approximately + $difference_of_day_number);
                break;
            default:
                unset($daynum_firstday, $daynum_lastday, $total_days, $total_weeks_approximately);
                return false;
        }

        unset($daynum_firstday, $daynum_firstday_offset, $daynum_lastday, $difference_of_day_number, $total_days, $total_weeks_approximately);
        if (isset($total_weeks)) {
            return $total_weeks;
        }
    }// weeksInMonth


}
