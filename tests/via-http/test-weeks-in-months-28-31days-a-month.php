<?php
require __DIR__.DIRECTORY_SEPARATOR.'includes.php';


$Calendar = new \Rundiz\Calendar\Calendar();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Example weeks in months for all 28 - 31 days a month</title>
        <style>
            .container {
                display: table;
                width: 100%;
            }
            .container:after {
                clear: both;
                content: '';
                display: table;
            }
            .container .columns {
                display: table-cell;
                margin: 0 10px;
                width: 33.33%;
            }

            .months-calendar {
                max-width: 600px;
            }
        </style>
    </head>
    <body>
        <h1>Example weeks in months for all 28 - 31 days a month</h1>
        <!--<div class="container">
            <div class="columns">
                <h2>Sunday first</h2>
                <img src="weeks-in-months-sunday-first.jpg" alt="" class="months-calendar">
            </div>
            <div class="columns">
                <h2>Monday first</h2>
                <img src="weeks-in-months-monday-first.jpg" alt="" class="months-calendar">
            </div>
            <div class="columns">
                <h2>Tuesday first</h2>
                <img src="weeks-in-months-tuesday-first.jpg" alt="" class="months-calendar">
            </div>
        </div>-->
        <?php
        $day_of_week = array(
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        );
        $test_months_years = array(
            array(
                '02-2009',
                '02-2010',
                '02-2011',
                '02-2006',
                '02-2007',
                '02-2013',
                '02-2014',
            ),
            array(
                '02-2032',
                '02-2016',
                '02-2028',
                '02-2012',
                '02-2024',
                '02-2036',
                '02-2020',
            ),
            array(
                '11-2015',
                '06-2015',
                '04-2014',
                '04-2015',
                '11-2012',
                '06-2012',
                '06-2013',
            ),
            array(
                '03-2015',
                '12-2014',
                '07-2014',
                '10-2014',
                '05-2014',
                '03-2013',
                '03-2014',
            ),
        );

        echo "\n";
        foreach ($test_months_years as $set_of_months) {
            echo '<div class="container">'."\n";
            for ($firstday_of_week = 0; $firstday_of_week <= 2; $firstday_of_week++) {
                echo '    <div class="columns">'."\n";
                echo '        First day of week is '.$day_of_week[$firstday_of_week].'<br><br>'."\n";
                if (is_array($set_of_months)) {
                    foreach ($set_of_months as $month_year) {
                        list($month, $year) = explode('-', $month_year);
                        echo '        '.date('F Y', mktime(0, 0, 0, $month, 1, $year)).' ('.date('t', mktime(0, 0, 0, $month, 1, $year)).' days in this month)<br>'."\n";
                        echo '        1st day of this month is '.date('l', mktime(0, 0, 0, $month, 1, $year)).'<br>'."\n";
                        echo '        Total weeks: ';
                        $result = $Calendar->weeksInMonth($month, $year, $firstday_of_week);
                        var_dump($result);
                        echo "\n";
                        unset($month, $result, $year);
                    }
                    unset($month_year);
                }
                echo '    </div>'."\n";
            }
            unset($firstday_of_week);
            echo '</div>'."\n";
            echo '<hr>'."\n";
        }
        unset($set_of_months, $test_months_years);
        ?> 
        <footer><small><a href="http://www.timeanddate.com/calendar/monthly.html" target="timeanddate.com">Check total weeks in a month in the calendar from timeanddate.com</a></small></footer>
    </body>
</html>
<?php
// clear.
unset($Calendar);