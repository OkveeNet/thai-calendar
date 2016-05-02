<?php
require __DIR__.DIRECTORY_SEPARATOR.'includes.php';

$Calendar = new \Rundiz\Calendar\Calendar();
$Calendar->viewing_date = date('Y');

$date = new \DateTime(date('Y-m-d'));
$date->add(new \DateInterval('P1D'));
$tomorrow = $date->format('Y-m-d');
$date->add(new \DateInterval('P1D'));
$after_tomorrow = $date->format('Y-m-d');
unset($date);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Calendar test get data for year</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Calendar test get data for year</h1>
                    <p>Test generate the calendar data but not render as HTML yet. Viewing date: <?php echo $Calendar->viewing_date; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    $events = array(
                        array(
                            'date_from' => date('Y-m-d 00:00:00'),
                            'date_to' => $tomorrow.' 01:00:00',
                            'title' => 'Event today to tomorrow +1hr.',
                        ),
                        array(
                            'date_from' => date('Y-m-d 00:50:00'),
                            'date_to' => $after_tomorrow.' 16:00:00',
                            'title' => 'Event today to after tomorrow 00:50 to 16:00.',
                        ),
                        'special_event1' => array(
                            'date_from' => $tomorrow.' 10:00:00',
                            'date_to' => $tomorrow.' 15:00:00',
                            'title' => 'Event tomorrow with special key name',
                        ),
                        array(
                            'date_from' => $tomorrow.' 11:00:00',
                            'date_to' => $tomorrow.' 15:00:00',
                            'title' => 'Event tomorrow 11:00 to 15:00',
                        ),
                        array(
                            'date_from' => $tomorrow.' 23:40:00',
                            'date_to' => $after_tomorrow.' 02:30:00',
                            'title' => 'Event tomorrow to after tomorrow 23:40 to 02:30',
                        ),
                        array(
                            'date_from' => $after_tomorrow.' 08:00:00',
                            'date_to' => $after_tomorrow.' 23:59:00',
                            'title' => 'Event after tomorrow 08:00 to 23:59',
                        ),
                    );
                    echo '<h3>Event data:</h3><pre>'.print_r($events, true).'</pre>'."\n";
                    $Calendar->setEvents($events);// Set once use forever until clear() or unset the class variable.
                    unset($events);
                    $Calendar->viewing_date = date('Y-m');
                    ?> 
                </div>
                <div class="col-sm-8">
                    <h2>Year</h2>
                    <?php
                    $Calendar->first_day_of_week = 2;
                    echo '<h3>Get calendar data:</h3><pre>'.print_r($Calendar->getCalendarData('year'), true).'</pre>'."\n";
                    $Calendar->clear();
                    ?> 
                </div>
            </div>
        </div><!--.container-fluid-->
    </body>
</html>
<?php
// clear
unset($after_tomorrow, $Calendar, $tomorrow);