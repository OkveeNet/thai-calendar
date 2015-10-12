<?php
/**
 * @author mr.v
 * @website http://okvee.net
 */

require(dirname(__FILE__)."/calendar.php");

$calendar = new calendar();
$calendar->page_url = "test.php?";
$calendar->year_be = true;// ไม่กำหนดก็ได้
$calendar->language = "thai";// ไม่กำหนดก็ได้

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Calendar</title>
		<style type="text/css" media="all">
			table.okv_calendar {border:1px solid #aaa; border-collapse: collapse; font-family: Tahoma, Geneva, sans-serif; font-size: 13px; width: 100%;}
			.okv_calendar td {border:1px solid #aaa; border-collapse: collapse; clear: both;}
			
			.okv_calendar .blank {background: #f5f5f5; cursor: default !important;}
			
			.okv_calendar .calendar_y_m {border-spacing: 0px; padding: 0;}
			.okv_calendar .calendar_y_m table.calendar_year_month {border: none; border-collapse: collapse; width: 100%;}
			.okv_calendar .calendar_y_m table.calendar_year_month td {border: none; border-right: 1px solid #aaa;}
			.okv_calendar .calendar_y_m table.calendar_year_month td:last-child {border: none;}
				.okv_calendar .month_year {background: #bbb; color: #fff; font-weight: bold; font-size: 16px; padding: 5px; text-align: center;}
				.okv_calendar .prev_month, .okv_calendar .next_month {background: #bbb; color: #fff; font-weight: bold; text-align: center; width:30px;}
				.okv_calendar .prev_month a, .okv_calendar .next_month a {color: #fff; display: block; line-height: 30px; text-decoration: none;}
				.okv_calendar .prev_year, .okv_calendar .next_year {background: #bbb; color: #fff; font-weight: bold; text-align: center; width:30px;}
				.okv_calendar .prev_year a, .okv_calendar .next_year a {color: #fff; display: block; line-height: 30px; text-decoration: none;}
			
			.okv_calendar .calendar_date {cursor: pointer; font-size: 15px; font-style: italic; height:100px; padding: 5px; vertical-align: top; width:14.2%;}
			.okv_calendar .calendar_date a {color: #143270; text-decoration: none;}
			.okv_calendar .calendar_date .calendar_appointment {color:#555; display: inline-block; float: right; font-size: 13px; font-style: normal; vertical-align: top; width:48%;}
			.okv_calendar .calendar_date .special_day {color:#555; display: inline-block; font-size: 12px; vertical-align: top; width:48%;}
			.okv_calendar .calendar_week_day {background: #ddd; font-weight: bold; padding: 5px; text-align: center; width:14.2%;}
			.okv_calendar .click_date {background: #fffcee;}
			.okv_calendar .current_date {background: #fff5f5; border: 2px solid #ffa0a0;}
			.okv_calendar .have_appointment {background: #fff8f1;}
		</style>
	</head>
	<body>
		<h1>ปฏิทิน</h1>

<?php echo $calendar->display();
unset($calendar);?>

		<hr />
		
		
		<h2>ตัวอย่างการใช้งานกับส่วนข้อมูลภายนอก</h2>
		<p>ตัวอย่างการใช้งานกับส่วนข้อมูลภายนอก เช่น กำหนดวันนัดแล้วแสดงในปฏิทิน</p>
		
<?php
$calendar = new calendar();
$calendar->page_url = "test.php?";
//กำหนดข้อมูลวันนัด กรณีนี้อาจเป็นการดึงข้อมูลจาก db ก็ได้ขอแค่รูปแบบวันที่ถูกต้องก็พอ คือ ปีค.ศ. เดือน xx วัน xx
$appointment[]['date'][date('Y-m')."-15"] = "หมอฟันนัด";
$appointment[]['date'][date('Y-m')."-28"] = "นัดหมอฟัน";
$appointment[]['date'][date('Y-')."-28"] = "หมอนัดฟัน";
$appointment[]['date'][date('Y-m')."-29"] = "นัดฟันหมอ";
$appointment[]['date'][date('Y-m')."-30"] = "ฟันนัดหมอ";
$calendar->appointment = $appointment;
echo $calendar->display();
unset($calendar);
?>

	</body>
</html>