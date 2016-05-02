<?php
require __DIR__.DIRECTORY_SEPARATOR.'includes.php';

$Calendar = new \Rundiz\Calendar\Calendar();
$Calendar->base_url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$Calendar->viewing_date = (isset($_GET['viewdate']) ? strip_tags($_GET['viewdate']) : date('Y'));

$date = new \DateTime(date('Y-m-d'));
$date->add(new \DateInterval('P1D'));
$tomorrow = $date->format('Y-m-d');
$date->add(new \DateInterval('P1D'));
$after_tomorrow = $date->format('Y-m-d');
$date->add(new \DateInterval('P10D'));
$next_12days = $date->format('Y-m-d');
unset($date);
$date = new \DateTime(date('Y-m-28'));
$date->add(new \DateInterval('P5D'));
$next_5days_after28 = $date->format('Y-m-d');
unset($date);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Calendar scope year</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="generator-simple.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Calendar scope year</h1>
                    <p>Test generate the calendar data as HTML. Viewing date: <?php echo $Calendar->viewing_date; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <h2>Event data</h2>
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
                        array(
                            'date_from' => $next_12days.'',
                            'date_to' => $next_12days.'',
                            'title' => 'Event next 12 days',
                        ),
                        array(
                            'date_from' => date('Y-m-28'),
                            'date_to' => $next_5days_after28,
                            'title' => 'Event 28 this month to next 5 days',
                        ),
                    );
                    echo '<pre>'.print_r($events, true).'</pre>'."\n";
                    $Calendar->setEvents($events);// Set once use forever until clear() or unset the class variable.
                    unset($events);
                    ?> 
                </div>
                <div class="col-sm-10">
                    <h2>Generated Calendar</h2>
                    <?php
                    $Calendar->first_day_of_week = 6;
                    echo $Calendar->display('year');
                    $Calendar->clear();
                    ?> 
                </div>
            </div>
        </div><!--.container-fluid-->


        <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
        <script src="common-calendar-functions.js"></script>
    </body>
</html>
<?php
// clear
unset($after_tomorrow, $Calendar, $next_12days, $next_5days_after28, $tomorrow);