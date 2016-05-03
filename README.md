# Thai Calendar

The calendar in Thai language (also support multi languages). ปฏิทินภาษาไทย (และรองรับได้หลายภาษา).

[![Latest Stable Version](https://poser.pugx.org/rundiz/thai-calendar/v/stable)](https://packagist.org/packages/rundiz/thai-calendar)
[![License](https://poser.pugx.org/rundiz/thai-calendar/license)](https://packagist.org/packages/rundiz/thai-calendar)
[![Total Downloads](https://poser.pugx.org/rundiz/thai-calendar/downloads)](https://packagist.org/packages/rundiz/thai-calendar)

This calendar component can display the calendar in multiple scope such as day, week, month, year. You can add events, or appointments to display in the calendar.
To get start is very easy, just set few things to the class properties and display.

## Usage
### Basic usage
In this example, it is just displaying the calendar without events or appointments for easy to understand.

```php
// If you did not install it via Composer, you have to include/require these files.
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Rundiz' . DIRECTORY_SEPARATOR . 'Calendar' . DIRECTORY_SEPARATOR . 'Calendar.php';
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Rundiz' . DIRECTORY_SEPARATOR . 'Calendar' . DIRECTORY_SEPARATOR . 'Generators' . DIRECTORY_SEPARATOR . 'GeneratorInterface.php';
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Rundiz' . DIRECTORY_SEPARATOR . 'Calendar' . DIRECTORY_SEPARATOR . 'Generators' . DIRECTORY_SEPARATOR . 'GeneratorAbstractClass.php';
// The files below is calendar HTML generator. Choose just only one.
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Rundiz' . DIRECTORY_SEPARATOR . 'Calendar' . DIRECTORY_SEPARATOR . 'Generators' . DIRECTORY_SEPARATOR . 'Simple.php';
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Rundiz' . DIRECTORY_SEPARATOR . 'Calendar' . DIRECTORY_SEPARATOR . 'Generators' . DIRECTORY_SEPARATOR . 'Bootstrap3.php';

$Calendar = new \Rundiz\Calendar\Calendar();
// Set your base URL for links to other date/month/year in the same page.
$Calendar->base_url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
// Set the viewing date to let calendar component knows that what is currently date you are looking at.
$Calendar->viewing_date = (isset($_GET['viewdate']) ? strip_tags($_GET['viewdate']) : date('Y-m-d'));
// Set first day of the week. You can ignore this property because it is set to 0 (Sunday) by default. Set to 0 for Sunday, 1 for Monday to 6 for Saturday.
$Calendar->first_day_of_week = 1;
// Display the calendar. 
// The first argument in this method is scope. You can set to 'day', 'week', 'month', 'year'.
// The second argument is the generator class name in case that you want something different.
echo $Calendar->display('day');
// Call to clear to clear and reset everything and make it ready to begins again.
$Calendar->clear();
```

### Use other generator
```php
$Calendar = new \Rundiz\Calendar\Calendar();
$Calendar->base_url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$Calendar->viewing_date = (isset($_GET['viewdate']) ? strip_tags($_GET['viewdate']) : date('Y-m-d'));
// We already have 2 generators for you to use. 1 is Simple and 2 is Bootstrap3. Use its class name in second argument of display() method.
echo $Calendar->display('year', '\\Rundiz\\Calendar\\Generators\\Bootstrap3');
$Calendar->clear();
```

### Events, Appointments
The events or appointments for scope day, week, month, year always use the same array format. Let's see the example.

```php
// Assume that today is 2016-05-02.
$events = array (
  array (
    'date_from' => '2016-05-02 00:00:00',
    'date_to' => '2016-05-03 01:00:00',
    'title' => 'Event today to tomorrow +1hr.',
  ),
  array (
    'date_from' => '2016-05-02 00:50:00',
    'date_to' => '2016-05-04 16:00:00',
    'title' => 'Event today to after tomorrow 00:50 to 16:00.',
  ),
  'special_event1' => array (
    'date_from' => '2016-05-03 10:00:00',
    'date_to' => '2016-05-03 15:00:00',
    'title' => 'Event tomorrow with special key name',
  ),
  array (
    'date_from' => '2016-05-03 11:00:00',
    'date_to' => '2016-05-03 15:00:00',
    'title' => 'Event tomorrow 11:00 to 15:00',
  ),
  array (
    'date_from' => '2016-05-03 23:40:00',
    'date_to' => '2016-05-04 02:30:00',
    'title' => 'Event tomorrow to after tomorrow 23:40 to 02:30',
  ),
  array (
    'date_from' => '2016-05-04 08:00:00',
    'date_to' => '2016-05-04 23:59:00',
    'title' => 'Event after tomorrow 08:00 to 23:59',
  ),
  array (
    'date_from' => '2016-05-14',
    'date_to' => '2016-05-14',
    'title' => 'Event next 12 days',
  ),
  array (
    'date_from' => '2016-05-28',
    'date_to' => '2016-06-02',
    'title' => 'Event 28 this month to next 5 days',
  ),
) ;
```

The array key 'date_from' and 'date_to' are required, the key 'title' is optional. You can add more array key into this data but you have to create generator yourself to support it.
Set to event to the calendar use `setEvents()` method.

```php
$Calendar = new \Rundiz\Calendar\Calendar();
$Calendar->base_url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$Calendar->viewing_date = (isset($_GET['viewdate']) ? strip_tags($_GET['viewdate']) : date('Y-m-d'));
$Calendar->setEvents($events);
echo $Calendar->display('month');
$Calendar->clear();
```

## Customize
### Change locale
You can change the language to use other language (or locale). To do this call to the 'locale' property of Calendar class.
```php
$Calendar->locale = array('en_UK.utf8', 'en_UK', 'en');
```
For more information about locale, please take a look at [http://php.net/manual/en/function.setlocale.php][1]

### First day of week
You can use other day as first day of week instead of Sunday. Set 'first_day_of_week' property to the day number (0 = Sunday, 1 = Monday, 2 = Tuesday, ..., 6 = Saturday)
```php
$Calendar->first_day_of_week = 3;// Wednesday as firstday of week.
```

### Buddhist era (ปีพุทธศักราช)
You can set to use or not to use Buddhist era (BE). You can also change the difference year of Buddhist era and anno Domini (AD). By default we use 543.
```php
$Calendar->use_buddhist_era = true;// Set to false for not to use Buddhist era.
$Calendar->buddhist_era_offset = 543;
$Calendar->buddhist_era_offset_short = 43;// 2016 = 2559, 16 = 59.
```

## Screenshots
### Scope day calendar
[![Day calendar](http://i.imgur.com/uqmd4li.jpg "Day calendar")][ss1]

### Scope week calendar
[![Week calendar](http://i.imgur.com/NPe0fKq.jpg "Week calendar")][ss2]

### Scope month calendar
[![Month calendar](http://i.imgur.com/mOpEQ1j.jpg "Month calendar")][ss3]

### Scope year calendar
[![Year calendar](http://i.imgur.com/fjUGqMz.jpg "Year calendar")][ss4]

[1]: http://php.net/manual/en/function.setlocale.php
[ss1]: http://imgur.com/uqmd4li
[ss2]: http://imgur.com/NPe0fKq
[ss3]: http://imgur.com/mOpEQ1j
[ss4]: http://imgur.com/fjUGqMz