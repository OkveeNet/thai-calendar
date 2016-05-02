<?php
/**
 * Rundiz Calendar component.
 * 
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rundiz\Calendar\Generators;

interface GeneratorInterface
{


    /**
     * Set the calendar data for use when generate HTML.
     * 
     * @param array $data The calendar data in array format.
     */
    public function __construct(array $data);


    /**
     * Display the calendar in HTML from specified scope.
     * 
     * @param string $scope Scope of the calendar you want to display. (day, week, month, year)
     * @return string Return generated calendar HTML.
     */
    public function display($scope = 'month');


}
