<?php
require __DIR__.DIRECTORY_SEPARATOR.'includes.php';


$Calendar = new \Rundiz\Calendar\Calendar();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Test weeks in months for year 2014</title>
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
        <h1>Test weeks in months for year 2014</h1>
        <!--<div class="container">
            <div class="columns">
                <h2>Sunday first</h2>
                <img src="2014-sun-first.jpg" alt="sunday first calendar" class="months-calendar">
            </div>
            <div class="columns">
                <h2>Monday first</h2>
                <img src="2014-mon-first.jpg" alt="monday first calendar" class="months-calendar">
            </div>
            <div class="columns">
                <h2>Tuesday first</h2>
                <img src="2014-tue-first.jpg" alt="tuesday first calendar" class="months-calendar">
            </div>
        </div>-->
        <?php
        $test_months_years = array(
            array(
                '01-2014',
                '02-2014',
                '03-2014',
                '04-2014',
                '05-2014',
                '06-2014',
                '07-2014',
                '08-2014',
                '09-2014',
                '10-2014',
                '11-2014',
                '12-2014',
            ),
        );

        echo "\n";
        foreach ($test_months_years as $set_of_months) {
            echo '<div class="container">'."\n";
            for ($firstday_of_week = 0; $firstday_of_week <= 2; $firstday_of_week++) {
                echo '    <div class="columns">'."\n";
                if (is_array($set_of_months)) {
                    foreach ($set_of_months as $month_year) {
                        list($month, $year) = explode('-', $month_year);
                        echo '        '.date('F Y', mktime(0, 0, 0, $month, 1, $year)).'<br>'."\n";
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
        <footer><small><a href="http://www.timeanddate.com/calendar/custom.html?year=2014&country=68&cwf=______&typ=0&display=3&df=1" target="timeanddate.com">Check total weeks in a month in the calendar from timeanddate.com</a></small></footer>
    </body>
</html>
<?php
// clear.
unset($Calendar);