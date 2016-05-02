<?php
/**
 * Rundiz Calendar component.
 * 
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rundiz\Calendar\Generators;

use \Rundiz\Calendar\Generators\GeneratorAbstractClass;

/**
 * The calendar HTML generator for generate calendar in HTML from scope you want.
 * 
 * @package Calendar
 * @since Version 2.0
 * @author Vee W.
 */
class Bootstrap3 extends GeneratorAbstractClass
{


    /**
     * {@inheritDoc}
     */
    public function display($scope = 'month')
    {
        $scope = strtolower($scope);

        switch ($scope) {
            case 'year':
                return $this->generateCalendarScopyYear();
            case 'month':
                return $this->generateCalendarScopeMonth();
            case 'week':
                return $this->generateCalendarScopeWeek();
            case 'day':
                return $this->generateCalendarScopeDay();
            default:
                break;
        }
    }// display


    /**
     * Generate calendar scope day.
     * 
     * @return string Return generated calendar HTML.
     */
    public function generateCalendarScopeDay()
    {
        if ($this->validateRequiredData() !== true) {
            return null;
        }

        $output = '';
        $base_url = $this->base_url.(strpos($this->base_url, '?') !== false ? '&amp;' : '?');

        if (array_key_exists('viewing_date', $this->calendar_data) && is_array($this->calendar_data['viewing_date']) && !empty($this->calendar_data['viewing_date'])) {
            // display day heading.
            $output .= '<div class="rundiz-date-navigation">'."\n";
            $output .= '  <a href="'.$base_url.'viewdate='.date('Y-m-d').'" class="btn btn-default">'.static::__('Today').'</a>'."\n";
            $output .= '  <div class="btn-group" role="group">'."\n";
            $date = new \DateTime($this->calendar_data['viewing_date']['full_date']);
            $date->sub(new \DateInterval('P1D'));
            $output .= '    <a href="'.$base_url.'viewdate='.$date->format('Y-m-d').'" class="btn btn-default">&lt;</a>'."\n";
            $date = new \DateTime($this->calendar_data['viewing_date']['full_date']);
            $date->add(new \DateInterval('P1D'));
            $output .= '    <a href="'.$base_url.'viewdate='.$date->format('Y-m-d').'" class="btn btn-default">&gt;</a>'."\n";
            unset($date);
            $output .= '  </div>'."\n";
            $output .= '</div>'."\n";
            $output .= '<h3>'.sprintf(static::__('Date: %s'), $this->calendar_data['viewing_date']['day_full'].' '.intval($this->calendar_data['viewing_date']['date']).' '.$this->calendar_data['viewing_date']['month_name_full'].' '.$this->calendar_data['viewing_date']['year_buddhist_era_full']).'</h3>'."\n";
        }

        if (array_key_exists('calendar', $this->calendar_data) && is_array($this->calendar_data['calendar']) && array_key_exists('times', $this->calendar_data['calendar']) && is_array($this->calendar_data['calendar']['times'])) {
            // display times calendar.
            $today_class = '';
            if (date('Y-m-d') == $this->calendar_data['viewing_date']['full_date']) {
                $today_class = ' is-today today-calendar';
            }
            $output .= '<table class="rundiz-calendar rundiz-calendar-scope-day'.$today_class.' table table-bordered table-condensed">'."\n";
            $output .= '  <tbody>'."\n";
            $odd = true;
            foreach ($this->calendar_data['calendar']['times'] as $time => $items) {
                $output .= '    <tr>'."\n";
                $current_time_class = '';
                $date = new \DateTime();
                $date->setTime($date->format('H'), 0, 0);
                if ($date->format('H:i') == $time) {
                    $current_time_class = ' is-current-hour';
                }
                unset($date);
                if ($odd === true) {
                    $output .= '      <td class="time'.$current_time_class.'" rowspan="2">'.$time.'</td>'."\n";
                }
                unset($current_time_class);
                $output .= '      <td class="event-column'.($odd === false ? ' time-half' : '').'">'."\n";
                $output .= '        <div class="events">'."\n";
                if (is_array($items)) {
                    foreach ($items as $event_key => $event_item) {
                        if (is_array($event_item)) {
                            $output .= '          ';
                            $output .= '<div class="event event-id-'.strip_tags(strval($event_key)).' event-from-'.str_replace(array(' ', ':'), '', $event_item['date_from']).' event-to-'.str_replace(array(' ', ':'), '', $event_item['date_to']).(!is_int($event_key) ? ' label-success' : ' label-primary').' label" data-event-id="'.strip_tags($event_key).'">';
                            if (array_key_exists('title', $event_item)) {
                                $output .= $event_item['title'];
                            }
                            $output .= '</div>';
                            $output .= "\n";
                        }
                    }// endforeach ($items or event)
                    unset($event_item, $event_key);
                }
                $output .= '        </div>'."\n";
                $output .= '      </td>'."\n";
                $output .= '    </tr>'."\n";
                if ($odd === true) {
                    $odd = false;
                } else {
                    $odd = true;
                }
            }// endforeach;
            unset($items, $odd, $time, $today_class);
            $output .= '  </tbody>'."\n";
            $output .= '</table>'."\n";
        }

        unset($base_url);
        return $output;
    }// generateCalendarScopeDay


    /**
     * Generate calendar scope month.
     * 
     * @return string Return generated calendar HTML.
     */
    private function generateCalendarScopeMonth()
    {
        if ($this->validateRequiredData() !== true) {
            return null;
        }

        $output = '';
        $base_url = $this->base_url.(strpos($this->base_url, '?') !== false ? '&amp;' : '?');

        if (array_key_exists('viewing_date', $this->calendar_data) && is_array($this->calendar_data['viewing_date']) && !empty($this->calendar_data['viewing_date'])) {
            // display day heading.
            $output .= '<div class="rundiz-date-navigation">'."\n";
            $output .= '  <a href="'.$base_url.'viewdate='.date('Y-m').'" class="btn btn-default">'.static::__('Today').'</a>'."\n";
            $output .= '  <div class="btn-group" role="group">'."\n";
            $date = new \DateTime($this->calendar_data['viewing_date']['full_date']);
            $date->sub(new \DateInterval('P1M'));
            $output .= '    <a href="'.$base_url.'viewdate='.$date->format('Y-m').'" class="btn btn-default">&lt;</a>'."\n";
            $date = new \DateTime($this->calendar_data['viewing_date']['full_date']);
            $date->add(new \DateInterval('P1M'));
            $output .= '    <a href="'.$base_url.'viewdate='.$date->format('Y-m').'" class="btn btn-default">&gt;</a>'."\n";
            unset($date);
            $output .= '  </div>'."\n";
            $output .= '</div>'."\n";
            $output .= '<h3>'.sprintf(static::__('Month: %s'), $this->calendar_data['viewing_date']['month_name_full'].' '.$this->calendar_data['viewing_date']['year_buddhist_era_full']).'</h3>'."\n";
        }

        if (array_key_exists('calendar', $this->calendar_data) && is_array($this->calendar_data['calendar'])) {
            // open calendar table
            $output .= '<table class="rundiz-calendar rundiz-calendar-scope-month table table-bordered table-condensed">'."\n";
            // display month navigation.
            $output .= '  <thead>'."\n";
            $output .= '    <tr class="month-navigation-row">'."\n";
            $output .= '      <th class="month-navigation-columns" colspan="7">'."\n";
            if (array_key_exists('year_navigation', $this->calendar_data['calendar']) && array_key_exists('month_navigation', $this->calendar_data['calendar'])) {
                // store the useful data into variables.
                $current_month_number = $this->calendar_data['calendar']['month_navigation']['current_month_number'];
                $current_month_year_full = $this->calendar_data['calendar']['month_navigation']['current_month_year_full'];
                $output .= '        <table class="month-navigation-table">'."\n";
                $output .= '          <tbody>'."\n";
                $output .= '            <tr>'."\n";
                $output .= '              <td class="previous-year"><a href="'.$base_url.'viewdate='.$this->calendar_data['calendar']['year_navigation']['previous'].'-'.$this->calendar_data['calendar']['month_navigation']['current_month_number'].'">&laquo;</a>'.'</td>'."\n";
                $output .= '              <td class="previous-month"><a href="'.$base_url.'viewdate='.$this->calendar_data['calendar']['month_navigation']['previous'].'">&lsaquo;</a>'.'</td>'."\n";
                $output .= '              <td class="current-month month-name">'.$this->calendar_data['calendar']['month_navigation']['current_month_name_full'].' '.$this->calendar_data['calendar']['month_navigation']['current_month_year_buddhist_era_full'].'</td>'."\n";
                $output .= '              <td class="next-month"><a href="'.$base_url.'viewdate='.$this->calendar_data['calendar']['month_navigation']['next'].'">&rsaquo;</a>'.'</td>'."\n";
                $output .= '              <td class="next-year"><a href="'.$base_url.'viewdate='.$this->calendar_data['calendar']['year_navigation']['next'].'-'.$this->calendar_data['calendar']['month_navigation']['current_month_number'].'">&raquo;</a>'.'</td>'."\n";
                $output .= '            </tr>'."\n";
                $output .= '          </tbody>'."\n";
                $output .= '        </table>'."\n";
            }
            $output .= '      </th>'."\n";
            $output .= '    </tr>'."\n";
            $output .= '  </thead>'."\n";
            // end display month navigation.
            $output .= '  <tbody>'."\n";
            // display days heading
            if (array_key_exists('days_heading', $this->calendar_data['calendar']) && is_array($this->calendar_data['calendar']['days_heading']) && !empty($this->calendar_data['calendar']['days_heading'])) {
                $output .= '    <tr class="days-row">'."\n";
                foreach ($this->calendar_data['calendar']['days_heading'] as $day_number => $day_items) {
                    $output .= '      <td class="day-'.$day_number.'">'.$day_items['day_short'].'</td>'."\n";
                }// endforeach; $this->calendar_data['calendar']['days_heading']
                unset($day_items, $day_number);
                $output .= '    </tr>'."\n";
            }
            // end display dats heading
            // display the dates in this month
            if (array_key_exists('weeks', $this->calendar_data['calendar']) && is_array($this->calendar_data['calendar']['weeks'])) {
                foreach ($this->calendar_data['calendar']['weeks'] as $week_number => $week_items) {
                    $output .= '    <tr class="dates-row week-'.$week_number.'">'."\n";
                    if (is_array($week_items) && array_key_exists('dates', $week_items) && is_array($week_items['dates'])) {
                        foreach ($week_items['dates'] as $day_number => $day_items) {
                            if (is_array($day_items)) {
                                if (array_key_exists('dates-before-month', $day_items)) {
                                    $date_before_month = true;
                                    unset($day_items['dates-before-month']);
                                }
                                if (array_key_exists('dates-after-month', $day_items)) {
                                    $date_after_month = true;
                                    unset($day_items['dates-after-month']);
                                }
                                $the_date = key($day_items);
                                if (array_key_exists($the_date, $day_items)) {
                                    $events = $day_items[$the_date];
                                }
                                $output .= '      <td class="date-cell date-'.$the_date;
                                if (isset($date_before_month) && $date_before_month === true) {
                                    $output .= ' date-before-month';
                                }
                                if (isset($date_after_month) && $date_after_month === true) {
                                    $output .= ' date-after-month';
                                }
                                if (date('Y-m-d') == $current_month_year_full.'-'.$current_month_number.'-'.$the_date && !isset($date_after_month) && !isset($date_before_month)) {
                                    $output .= ' is-today';
                                }
                                $output .= '">'."\n";
                                $output .= '        <div class="date-number"';
                                $output .= ' data-date="'.$the_date.'"';
                                $output .= ' data-day-number="'.$day_number.'"';
                                $output .= ' data-month="'.(isset($current_month_number) ? $current_month_number : '').'"';
                                $output .= ' data-year="'.(isset($current_month_year_full) ? $current_month_year_full : '').'"';
                                $output .= '>';
                                $output .= intval($the_date);
                                $output .= '</div>'."\n";
                                $output .= '        <div class="events">'."\n";
                                if (isset($events) && is_array($events)) {
                                    // loop special events first
                                    foreach ($events as $event_key => $event_item) {
                                        if (is_array($event_item)) {
                                            if (!is_int($event_key)) {
                                                $output .= '          <div class="event event-id-'.strip_tags(strval($event_key)).' event-from-'.str_replace(array(' ', ':'), '', $event_item['date_from']).' event-to-'.str_replace(array(' ', ':'), '', $event_item['date_to']).(!is_int($event_key) ? ' label-success' : ' label-primary').' label" data-event-id="'.strip_tags($event_key).'">';
                                                if (array_key_exists('title', $event_item)) {
                                                    $output .= $event_item['title'];
                                                }
                                                $output .= '</div>'."\n";
                                                unset($events[$event_key]);
                                            }
                                        }
                                    }// endforeach; $events;
                                    unset($event_item, $event_key);
                                    // loop normal events
                                    foreach ($events as $event_key => $event_item) {
                                        if (is_array($event_item)) {
                                            $output .= '          <div class="event event-id-'.strip_tags(strval($event_key)).' event-from-'.str_replace(array(' ', ':'), '', $event_item['date_from']).' event-to-'.str_replace(array(' ', ':'), '', $event_item['date_to']).(!is_int($event_key) ? ' label-success' : ' label-primary').' label" data-event-id="'.strip_tags($event_key).'">';
                                            if (array_key_exists('title', $event_item)) {
                                                $output .= $event_item['title'];
                                            }
                                            $output .= '</div>'."\n";
                                        }
                                    }// endforeach; $events;
                                    unset($event_item, $event_key);
                                }
                                $output .= '        </div>'."\n";
                                $output .= '      </td>'."\n";
                                unset($date_after_month, $date_before_month, $events, $the_date);
                            }
                        }// endforeach; $week_items['dates']
                        unset($day_items, $day_number);
                    }
                    $output .= '    </tr>'."\n";
                }// endforeach; $this->calendar_data['calendar']['weeks']
                unset($week_items, $week_number);
            }
            // end display the dates in this month
            $output .= '  </tbody>'."\n";
            // close calendar table
            $output .= '</table>'."\n";
            unset($current_month_number, $current_month_year_full);
        }

        unset($base_url);
        return $output;
    }// generateCalendarScopeMonth


    /**
     * Generate calendar scope week.
     * 
     * @return string Return generated calendar HTML.
     */
    private function generateCalendarScopeWeek()
    {
        if ($this->validateRequiredData() !== true) {
            return null;
        }

        $output = '';
        $base_url = $this->base_url.(strpos($this->base_url, '?') !== false ? '&amp;' : '?');

        if (isset($this->calendar_data['calendar']['dates']) && is_array($this->calendar_data['calendar']['dates'])) {
            // display day heading.
            $output .= '<div class="rundiz-date-navigation">'."\n";
            $dates = array_keys($this->calendar_data['calendar']['dates']);
            $first_date_of_week = min($dates);
            $last_date_of_week = max($dates);
            unset($dates);
            $output .= '  <a href="'.$base_url.'viewdate='.date('Y-m-d').'" class="btn btn-default">'.static::__('Today').'</a>'."\n";
            $output .= '  <div class="btn-group" role="group">'."\n";
            $date = new \DateTime($first_date_of_week);
            $date->sub(new \DateInterval('P7D'));
            $output .= '    <a href="'.$base_url.'viewdate='.$date->format('Y-m-d').'" class="btn btn-default">&lt;</a>'."\n";
            $date = new \DateTime($last_date_of_week);
            $date->add(new \DateInterval('P1D'));
            $output .= '    <a href="'.$base_url.'viewdate='.$date->format('Y-m-d').'" class="btn btn-default">&gt;</a>'."\n";
            unset($date, $first_date_of_week, $last_date_of_week);
            $output .= '  </div>'."\n";
            $output .= '</div>'."\n";
        }

        if (array_key_exists('calendar', $this->calendar_data) && is_array($this->calendar_data['calendar']) && array_key_exists('dates', $this->calendar_data['calendar']) && is_array($this->calendar_data['calendar']['dates'])) {
            // display days in a week calendar.
            $output .= '<div class="rundiz-calendar-columns-week row">'."\n";
            $day_count = 1;
            foreach ($this->calendar_data['calendar']['dates'] as $the_date => $calendar_times) {
                $today_class = '';
                if (date('Y-m-d') == $the_date) {
                    $today_class = ' is-today today-calendar';
                }
                $output .= '  <div class="column column-1-day col-xs-4">'."\n";
                $date = new \DateTime($the_date);
                $output .= '    <h3>'.static::unicodeStrftime('%a %d %b ', $date->getTimestamp()).($this->use_buddhist_era === true ? ($date->format('Y') + $this->buddhist_era_offset) : $date->format('Y')).'</h3>'."\n";
                unset($date);
                // display times calendar.
                $output .= '    <table class="rundiz-calendar rundiz-calendar-scope-day'.$today_class.' table table-bordered table-condensed">'."\n";
                $output .= '      <tbody>'."\n";
                $odd = true;
                foreach ($calendar_times['times'] as $time => $items) {
                    $output .= '        <tr>'."\n";
                    //if ($day_count == 1) {
                        $current_time_class = '';
                        $date = new \DateTime();
                        $date->setTime($date->format('H'), 0, 0);
                        if ($date->format('H:i') == $time) {
                            $current_time_class = ' is-current-hour';
                        }
                        unset($date);
                        if ($odd === true) {
                            $output .= '          <td class="time'.$current_time_class.'" rowspan="2">'.$time.'</td>'."\n";
                        }
                        unset($current_time_class);
                    //}
                    $output .= '          <td class="event-column'.($odd === false ? ' time-half' : '').'">'."\n";
                    $output .= '            <div class="events">'."\n";
                    if (is_array($items)) {
                        foreach ($items as $event_key => $event_item) {
                            if (is_array($event_item) && !empty($event_item)) {
                                $output .= '              ';
                                $output .= '<div class="event event-id-'.strip_tags(strval($event_key)).' event-from-'.str_replace(array(' ', ':'), '', $event_item['date_from']).' event-to-'.str_replace(array(' ', ':'), '', $event_item['date_to']).(!is_int($event_key) ? ' label-success' : ' label-primary').' label" data-event-id="'.strip_tags($event_key).'">';
                                if (array_key_exists('title', $event_item)) {
                                    $output .= $event_item['title'];
                                }
                                $output .= '</div>';
                                $output .= "\n";
                            }
                        }// endforeach ($items or event)
                        unset($event_item, $event_key);
                    }
                    $output .= '            </div>'."\n";
                    $output .= '          </td>'."\n";
                    $output .= '        </tr>'."\n";
                    if ($odd === true) {
                        $odd = false;
                    } else {
                        $odd = true;
                    }
                }// endforeach; $calendar_times['times']
                unset($items, $odd, $time, $today_class);
                $output .= '      </tbody>'."\n";
                $output .= '    </table>'."\n";
                // end display times calendar.
                $output .= '  </div><!--.column-1-day-->'."\n";
                if ($day_count % 3 == 0 && $day_count + 1 < 7) {
                    $output .= '</div><!--.rundiz-calendar-columns-week-->'."\n";
                    $output .= '<div class="rundiz-calendar-columns-week row">'."\n";
                }
                $day_count++;
            }// endforeach; $this->calendar_data['calendar']['dates']
            unset($calendar_times, $day_count, $the_date);
            $output .= '</div><!--.rundiz-calendar-columns-week-->'."\n";
        }

        unset($base_url);
        return $output;
    }// generateCalendarScopeWeek


    /**
     * Generate calendar scope year.
     * 
     * @return string Return generated calendar HTML.
     */
    private function generateCalendarScopyYear()
    {
        if ($this->validateRequiredData() !== true) {
            return null;
        }

        $output = '';
        $base_url = $this->base_url.(strpos($this->base_url, '?') !== false ? '&amp;' : '?');

        if (array_key_exists('viewing_date', $this->calendar_data) && is_array($this->calendar_data['viewing_date']) && !empty($this->calendar_data['viewing_date'])) {
            // display day heading.
            $output .= '<div class="rundiz-date-navigation">'."\n";
            $output .= '  <a href="'.$base_url.'viewdate='.date('Y').'" class="btn btn-default">'.static::__('Today').'</a>'."\n";
            $output .= '  <div class="btn-group" role="group">'."\n";
            $date = new \DateTime($this->calendar_data['viewing_date']['year'].'-01-01');
            $date->sub(new \DateInterval('P1Y'));
            $output .= '    <a href="'.$base_url.'viewdate='.$date->format('Y').'" class="btn btn-default">&lt;</a>'."\n";
            $date = new \DateTime($this->calendar_data['viewing_date']['year'].'-01-01');
            $date->add(new \DateInterval('P1Y'));
            $output .= '    <a href="'.$base_url.'viewdate='.$date->format('Y').'" class="btn btn-default">&gt;</a>'."\n";
            unset($date);
            $output .= '  </div>'."\n";
            $output .= '</div>'."\n";
            $output .= '<h3>'.sprintf(static::__('Year: %s'), $this->calendar_data['viewing_date']['year_buddhist_era_full']).'</h3>'."\n";
        }

        if (array_key_exists('calendar', $this->calendar_data) && is_array($this->calendar_data['calendar']) && array_key_exists('months', $this->calendar_data['calendar']) && is_array($this->calendar_data['calendar']['months'])) {
            // display months in a year calendar
            $output .= '<div class="rundiz-calendar-columns-year row">'."\n";
            foreach ($this->calendar_data['calendar']['months'] as $month_number => $month_items) {
                $output .= '  <div class="column column-1-month col-xs-12 col-sm-6 col-lg-4">'."\n";
                // display month in calendar.
                $output .= '<table class="rundiz-calendar rundiz-calendar-scope-month table table-bordered table-condensed">'."\n";
                // display month navigation.
                $output .= '  <thead>'."\n";
                $output .= '    <tr class="month-navigation-row">'."\n";
                $output .= '      <th class="month-navigation-columns" colspan="7">'."\n";
                if (array_key_exists('year_navigation', $month_items) && array_key_exists('month_navigation', $month_items)) {
                    // store the useful data into variables.
                    $current_month_number = $month_items['month_navigation']['current_month_number'];
                    $current_month_year_full = $month_items['month_navigation']['current_month_year_full'];
                    $output .= '        <table class="month-navigation-table">'."\n";
                    $output .= '          <tbody>'."\n";
                    $output .= '            <tr>'."\n";
                    $output .= '              <td class="current-month month-name">'.$month_items['month_navigation']['current_month_name_full'].' '.$month_items['month_navigation']['current_month_year_buddhist_era_full'].'</td>'."\n";
                    $output .= '            </tr>'."\n";
                    $output .= '          </tbody>'."\n";
                    $output .= '        </table>'."\n";
                }
                $output .= '      </th>'."\n";
                $output .= '    </tr>'."\n";
                $output .= '  </thead>'."\n";
                // end display month navigation.
                $output .= '  <tbody>'."\n";
                // display days heading
                if (array_key_exists('days_heading', $month_items) && is_array($month_items['days_heading']) && !empty($month_items['days_heading'])) {
                    $output .= '    <tr class="days-row">'."\n";
                    foreach ($month_items['days_heading'] as $day_number => $day_items) {
                        $output .= '      <td class="day-'.$day_number.'">'.$day_items['day_short'].'</td>'."\n";
                    }// endforeach; $month_items['days_heading']
                    unset($day_items, $day_number);
                    $output .= '    </tr>'."\n";
                }
                // end display dats heading
                // display the dates in this month
                if (array_key_exists('weeks', $month_items) && is_array($month_items['weeks'])) {
                    foreach ($month_items['weeks'] as $week_number => $week_items) {
                        $output .= '    <tr class="dates-row week-'.$week_number.'">'."\n";
                        if (is_array($week_items) && array_key_exists('dates', $week_items) && is_array($week_items['dates'])) {
                            foreach ($week_items['dates'] as $day_number => $day_items) {
                                if (is_array($day_items)) {
                                    if (array_key_exists('dates-before-month', $day_items)) {
                                        $date_before_month = true;
                                        unset($day_items['dates-before-month']);
                                    }
                                    if (array_key_exists('dates-after-month', $day_items)) {
                                        $date_after_month = true;
                                        unset($day_items['dates-after-month']);
                                    }
                                    $the_date = key($day_items);
                                    if (array_key_exists($the_date, $day_items)) {
                                        $events = $day_items[$the_date];
                                    }
                                    $output .= '      <td class="date-cell date-'.$the_date;
                                    if (isset($date_before_month) && $date_before_month === true) {
                                        $output .= ' date-before-month';
                                    }
                                    if (isset($date_after_month) && $date_after_month === true) {
                                        $output .= ' date-after-month';
                                    }
                                    if (date('Y-m-d') == $current_month_year_full.'-'.$current_month_number.'-'.$the_date && !isset($date_after_month) && !isset($date_before_month)) {
                                        $output .= ' is-today';
                                    }
                                    if (isset($events) && is_array($events) && !empty($events)) {
                                        $output .= ' have-events';
                                    }
                                    $output .= '">'."\n";
                                    $output .= '        <div class="date-number"';
                                    $output .= ' data-date="'.$the_date.'"';
                                    $output .= ' data-day-number="'.$day_number.'"';
                                    $output .= ' data-month="'.(isset($current_month_number) ? $current_month_number : '').'"';
                                    $output .= ' data-year="'.(isset($current_month_year_full) ? $current_month_year_full : '').'"';
                                    $output .= '>';
                                    $output .= intval($the_date);
                                    $output .= '</div>'."\n";
                                    $output .= '        <div class="events'.(isset($events) && is_array($events) && !empty($events) ? ' have-events' : '').'">'."\n";
                                    if (isset($events) && is_array($events)) {
                                        // loop special events first
                                        foreach ($events as $event_key => $event_item) {
                                            if (is_array($event_item)) {
                                                if (!is_int($event_key)) {
                                                    $output .= '          <div class="event event-id-'.strip_tags(strval($event_key)).' event-from-'.str_replace(array(' ', ':'), '', $event_item['date_from']).' event-to-'.str_replace(array(' ', ':'), '', $event_item['date_to']).(!is_int($event_key) ? ' label-success' : ' label-primary').' label" data-event-id="'.strip_tags($event_key).'">';
                                                    if (array_key_exists('title', $event_item)) {
                                                        $output .= $event_item['title'];
                                                    }
                                                    $output .= '</div>'."\n";
                                                    unset($events[$event_key]);
                                                }
                                            }
                                        }// endforeach; $events;
                                        unset($event_item, $event_key);
                                        // loop normal events
                                        foreach ($events as $event_key => $event_item) {
                                            if (is_array($event_item)) {
                                                $output .= '          <div class="event event-id-'.strip_tags(strval($event_key)).' event-from-'.str_replace(array(' ', ':'), '', $event_item['date_from']).' event-to-'.str_replace(array(' ', ':'), '', $event_item['date_to']).(!is_int($event_key) ? ' label-success' : ' label-primary').' label" data-event-id="'.strip_tags($event_key).'">';
                                                if (array_key_exists('title', $event_item)) {
                                                    $output .= $event_item['title'];
                                                }
                                                $output .= '</div>'."\n";
                                            }
                                        }// endforeach; $events;
                                        unset($event_item, $event_key);
                                    }
                                    $output .= '        </div>'."\n";
                                    $output .= '      </td>'."\n";
                                    unset($date_after_month, $date_before_month, $events, $the_date);
                                }
                            }// endforeach; $week_items['dates']
                            unset($day_items, $day_number);
                        }
                        $output .= '    </tr>'."\n";
                    }// endforeach; $month_items['weeks']
                    unset($week_items, $week_number);
                }
                // end display the dates in this month
                $output .= '  </tbody>'."\n";
                // close calendar table
                $output .= '</table>'."\n";
                unset($current_month_number, $current_month_year_full);
                // end display month in calendar.
                $output .= '  </div>'."\n";
            }// endforeach; $month_items['months']
            unset($month_items, $month_number);
            $output .= '</div>'."\n";
        }

        unset($base_url);
        return $output;
    }// generateCalendarScopyYear


    /**
     * Validate required calendar data.
     * 
     * @return boolean Return true on success, false on failure.
     */
    private function validateRequiredData()
    {
        if (!is_array($this->calendar_data) || empty($this->calendar_data)) {
            return false;
        }
        return true;
    }// validateRequiredData


}
