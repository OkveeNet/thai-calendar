<?php
/**
 * Rundiz Calendar component.
 * 
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rundiz\Calendar\Generators;

use \Rundiz\Calendar\Generators\GeneratorInterface;

/**
 * The generator abstract class.
 *
 * @package Calendar
 * @since Version 2.0
 * @author Vee W.
 */
abstract class GeneratorAbstractClass implements GeneratorInterface
{


    /**
     * @var sting Base URL for use with link to navigate the date.
     */
    public $base_url;
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
     * @var array The calendar data that was set via `setCalendarData()` method.
     */
    protected $calendar_data = array();


    /**
     * {@inheritDoc}
     */
    public function __construct(array $data)
    {
        $this->calendar_data = $data;
    }// __construct


    /**
     * Placeholder method for language editor program like Poedit to lookup for the words that is using this method.<br>
     * This method does nothing but for who use program like Poedit to search/lookup the words that is using this method to create translation.<br>
     * Example:<br>
     * There is this code in generator class. <code>static::__('Hello');</code><br>
     * Use Poedit to search for __ function to update/retreive the source text and translate it.
     * 
     * @param string $string The message to use.
     * @return string Return the same string.
     */
    protected static function __($string)
    {
        return $string;
    }// __


    /**
     * Clear and reset the generator.
     */
    public function clear()
    {
        $this->calendar_data = array();
    }// clear


    /**
     * Unicode version of strftime()
     * 
     * @param string $format The format. For more info please look at http://php.net/manual/en/function.strftime.php
     * @param integer $timestamp Timestamp
     * @return string Return value of strftime() function.
     */
    public static function unicodeStrftime($format, $timestamp = null)
    {
        if ($timestamp == null) {
            $timestamp = time();
        }

        $example_encoding = strftime('%a', $timestamp);
        $detect_encoding = mb_detect_encoding($example_encoding, mb_detect_order(), true);
        unset($example_encoding);

        return iconv($detect_encoding, 'UTF-8', strftime($format, $timestamp));
    }// unicodeStrftime


}
