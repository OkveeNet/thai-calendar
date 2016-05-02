<?php
require __DIR__.DIRECTORY_SEPARATOR.'includes.php';


$Calendar = new \Rundiz\Calendar\Calendar();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Test weeks in months for year 2016</title>
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
                width: 50%;
            }

            .months-calendar {
                max-width: 600px;
            }
        </style>
    </head>
    <body>
        <h1>Test weeks in months for year 2016</h1>
        <!--<div class="container">
            <div class="columns">
                <h2>Sunday first</h2>
                <img src="2016-sun-first.png" alt="sunday first calendar" class="months-calendar">
            </div>
            <div class="columns">
                <h2>Monday first</h2>
                <img src="2016-mon-first.png" alt="monday first calendar" class="months-calendar">
            </div>
        </div>-->
        <?php
        $test_months_years = array(
            array(
                '01-2016',
                '02-2016',
                '03-2016',
                '04-2016',
                '05-2016',
                '06-2016',
                '07-2016',
                '08-2016',
                '09-2016',
                '10-2016',
                '11-2016',
                '12-2016',
            ),
        );

        echo "\n";
        foreach ($test_months_years as $set_of_months) {
            echo '<div class="container">'."\n";
            for ($firstday_of_week = 0; $firstday_of_week <= 1; $firstday_of_week++) {
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
        <footer><small><a href="http://www.timeanddate.com/calendar/custom.html?year=2016&country=68&cwf=______&typ=0&display=3&df=1" target="timeanddate.com">Check total weeks in a month in the calendar from timeanddate.com</a></small></footer>
    </body>
</html>
<?php
// clear.
unset($Calendar);