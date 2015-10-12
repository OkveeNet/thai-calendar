<?php
/**
 * @author mr.v
 * @website http://okvee.net
 * todo: ทำปฏิทินจันทรคติ
 * refer from
 * http://en.wikipedia.org/wiki/Thai_solar_calendar
 * http://en.wikipedia.org/wiki/Thai_lunar_calendar
 * http://th.wikipedia.org/wiki/%E0%B8%9B%E0%B8%8F%E0%B8%B4%E0%B8%97%E0%B8%B4%E0%B8%99%E0%B8%88%E0%B8%B1%E0%B8%99%E0%B8%97%E0%B8%A3%E0%B8%84%E0%B8%95%E0%B8%B4%E0%B8%9B%E0%B8%B1%E0%B8%81%E0%B8%82%E0%B8%84%E0%B8%93%E0%B8%99%E0%B8%B2
 * http://www.larndham.net/calendar/pkn.php (source code)
 */


class calendar {


	public $language = "english";
	public $appointment = '';
	public $year_be = false;// ใช้ buddhist era, พ.ศ. ?
	public $page_url = '';// required. อาจกำหนดเป็น http://localhost/page.php?category=1& หรือ http://localhost/page.php? หรือ /page.php? ซึ่งคลาสจะเติมค่า get ต่างๆที่ต้องการต่อท้ายเอาเองได้


	private $get_date = '';
	private $get_month = '';
	private $get_year = '';


	function __construct() {
		if ( isset($_GET['get_date']) ) {$this->get_date = trim(strip_tags($_GET['get_date']));} else {$this->get_date = date("d");}
		if ( isset($_GET['get_month']) ) {$this->get_month = trim(strip_tags($_GET['get_month']));} else {$this->get_month = date("m");}
		if ( isset($_GET['get_year']) ) {$this->get_year = trim(strip_tags($_GET['get_year']));} else {$this->get_year = date("Y");}
	}// __construct


	function  __destruct() {
		unset($this->thai_calendar);
	}


	/**
	 * array key exists recursive
	 * from http://www.php.net/manual/en/function.array-key-exists.php by Benjamin*removethis*BeckATgmx.de
	 * @param mixed $needle
	 * @param array $haystack
	 * @return boolean
	 */
	function array_key_exists_r($needle, $haystack) {
	    $result = array_key_exists($needle, $haystack);
	    if ($result) return $result;
	    foreach ($haystack as $v) {
		if (is_array($v)) {
		    $result = $this->array_key_exists_r($needle, $v);
		}
		if ($result) return $result;
	    }
	    return $result;
	}// array_key_exists_r
	
	
	private function check_appointment($year = '', $month = '', $date = '') {
		$date = str_pad($date, 2, '0', STR_PAD_LEFT);
		if ( is_array($this->appointment) ) {
			if ( $this->array_key_exists_r($year."-".$month."-".$date, $this->appointment) == true ) {
				return " have_appointment";
			}
		}
	}// check_appointment
	

	/**
	 * check_required_property
	 * @return string/true return string error if found required is empty or wrong value.
	 */
	private function check_required_property() {
		if ( $this->page_url == null ) {
			return "Please set <strong>page_url</strong> property.";
			exit;
		}
		if ( !is_numeric($this->get_date) || !is_numeric($this->get_month) || !is_numeric($this->get_year) ) {
			return "Invalid get value.";
			exit;
		}
		return true;// all pass
	}// check_required_property

	
	/**
	 * display calendar
	 */
	function display() {
		// check required
		if ( $this->check_required_property() !== true ) {return $this->check_required_property();}
		// start render calendar table
		$output = "<!--start calendar-->\n";
		$output .= $this->display_start_table();
		
		$output .= "<tr><td class=\"calendar_y_m\" colspan=\"7\">\n\n";
		$output .= "<table class=\"calendar_year_month\">\n";
		$output .= $this->display_year_month();
		$output .= "</table>\n";
		$output .= "\n</td></tr>\n";

		// display week day and dates
		$output .= $this->display_week_days();
		$output .= $this->display_dates();
		// end display week day and dates
		
		$output .= $this->display_end_table();
		$output .= "<!--end calendar-->\n";
		return $output;
	}// display
	
	
	function display_appointment($year = '', $month = '', $date = '') {
		$date = str_pad($date, 2, '0', STR_PAD_LEFT);
		$output = "";
		if ( is_array($this->appointment) ) {
			if ( $this->array_key_exists_r($year."-".$month."-".$date, $this->appointment) == true ) {
				$output .= "<span class=\"calendar_appointment\">";
				foreach ( $this->appointment as $key => $item ) {
					if ( isset($item['date'][$year."-".$month."-".$date]) ) {
						$output .= $item['date'][$year."-".$month."-".$date] . "<br />";
					}
				}
				$output .= "</span>";
			}
		}
		return $output;
	}// display_appointment
	
	
	private function display_dates() {
		$output = "<tr>\n";
		$week_day = 0;
		// find end date => day of month (if 28/2/2011 is monday or 1 fill until sat or 6)
		$total_date = date("t", strtotime($this->get_year."-".$this->get_month));
		$last_date_day = date("w", strtotime($this->get_year."-".$this->get_month."-".$total_date));// got 0-6 where 0=sun, 6=sat
		if ( $last_date_day != '6' ) {
			$total_date = (6-$last_date_day)+$total_date;
		} else {
			$total_date = $total_date;
		}
		// loop for dates
		for ( $i=1; $i<=$total_date; $i++ ) {
			// check if date is really a date (eg. 31-feb-2011 is impossible)
			if ( checkdate($this->get_month, $i, $this->get_year) == false ) {
				$output .= "<td class=\"calendar_date blank\">&nbsp;</td>\n";
			} else {
				// match date to day. if not match set i to 1 (from the start eg. 1 of month is not sunday)
				if ( date("w", strtotime($this->get_year."-".$this->get_month."-".$i)) != $week_day ) {
					$i = 1;
				}
				// list date
				if ( date("w", strtotime($this->get_year."-".$this->get_month."-".$i)) == $week_day ) {
					$output .= "<td class=\"calendar_date" . $this->get_current_date($i) . $this->check_appointment($this->get_year, $this->get_month, $i) . " " . $this->get_current_click_date($i) . "\"";
						$output .= " onclick=\"window.location='".$this->page_url."get_year=".$this->get_year."&get_month=".$this->get_month."&get_date=".$this->get_date . "&click_date=".$this->get_year."-".$this->get_month."-".str_pad($i, 2, '0', STR_PAD_LEFT)."';\"";
						$output .= ">";
					$output .= "<a href=\"" . $this->page_url . "get_year=".$this->get_year."&get_month=".$this->get_month."&get_date=".$this->get_date . "&click_date=".$this->get_year."-".$this->get_month."-".str_pad($i, 2, '0', STR_PAD_LEFT) . "\">" . $i . "</a>";
					$output .= $this->display_special_days($this->get_year, $this->get_month, $i);
					$output .= $this->display_appointment($this->get_year, $this->get_month, $i);
					$output .= "</td>\n";
				} else {
					$output .= "<td class=\"calendar_date blank\">&nbsp;</td>\n";
				}
			}
			// count week day (sun, mon, tue, ...)
			$week_day++;
			if ( ($week_day+1) == 8 ) {
				$week_day = 0;
				$output .= "</tr>\n";
				$output .= "<tr>\n";
			}
		}
		$output .= "</tr>\n";
		$output = str_replace("<tr>\n</tr>\n", "", $output);
		return $output;
	}// display_dates
	
	
	private function display_get_next_month() {
		if ( ($this->get_month+1) == '13' ) {
			return $this->page_url."get_year=".($this->get_year+1)."&get_month=1"."&get_date=".$this->get_date;
		} else {
			return $this->page_url."get_year=".$this->get_year."&get_month=".str_pad(($this->get_month+1), 2, '0', STR_PAD_LEFT)."&get_date=".$this->get_date;
		}
	}// display_get_next_month
	
	
	private function display_get_prev_month() {
		if ( ($this->get_month-1) == '0' ) {
			return $this->page_url."get_year=".($this->get_year-1)."&get_month=12"."&get_date=".$this->get_date;
		} else {
			return $this->page_url."get_year=".$this->get_year."&get_month=".str_pad(($this->get_month-1), 2, '0', STR_PAD_LEFT) ."&get_date=".$this->get_date;
		}
	}// display_get_prev_month
	
	
	/**
	 * display_month_name
	 * @return string
	 */
	private function display_month_name() {
		if ( checkdate($this->get_month, $this->get_date, $this->get_year) ) {
			if ( $this->language == "thai" ) {
				$th_month = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
				return (isset($th_month[intval($this->get_month)-1]) ? $th_month[intval($this->get_month)-1] : "Could not match month.");
			} else {
				// english
				return date("F", strtotime($this->get_year."-".$this->get_month."-".$this->get_date));
			}
		} else {
			return "Invalid date value.";
		}
	}// display_month_name


	private function display_end_table() {
		return "</table>\n";
	}// display_end_table


	function display_special_days($year = '', $month = '', $date = '') {
		$output = "";
		if ( $this->language == "thai" ) {
			if ( $month == "01" && $date == "01" ) {$output .= "วันปีใหม่<br />";}
			elseif ( $month == "01" && $date == "16" ) {$output .= "วันครู<br />";}
			elseif ( $month == "02" && $date == "14" ) {$output .= "วันแห่งความรัก<br />";}
			elseif ( $month == "04" && $date == "06" ) {$output .= "วันจักรี<br />";}
			elseif ( $month == "04" && ($date >= "13" && $date <= "15") ) {$output .= "วันสงกรานต์<br />";}
			elseif ( $month == "05" && $date == "01" ) {$output .= "วันแรงงานแห่งชาติ<br />";}
			elseif ( $month == "05" && $date == "05" ) {$output .= "วันฉัตรมงคล<br />";}
			elseif ( $month == "06" && $date == "26" ) {$output .= "วันสุนทรภู่<br />";}
			elseif ( $month == "07" && $date == "01" ) {$output .= "วันหยุดครึ่งปีธนาคาร<br />";}
			elseif ( $month == "08" && $date == "12" ) {$output .= "วันแม่<br />";}
			elseif ( $month == "10" && $date == "23" ) {$output .= "วันปิยมหาราช<br />";}
			elseif ( $month == "12" && $date == "05" ) {$output .= "วันพ่อ<br />";}
			elseif ( $month == "12" && $date == "10" ) {$output .= "วันรัฐธรรมนูญ<br />";}
			elseif ( $month == "12" && $date == "31" ) {$output .= "วันสิ้นปี<br />";}
		} else {
			if ( $month == "01" && $date == "01" ) {$output .= "New year's day<br />";}
			elseif ( $month == "01" && $date == "16" ) {$output .= "Teacher day<br />";}
			elseif ( $month == "02" && $date == "14" ) {$output .= "Valentine's day<br />";}
			elseif ( $month == "04" && $date == "06" ) {$output .= "Chakri memorial day<br />";}
			elseif ( $month == "04" && ($date >= "13" && $date <= "15") ) {$output .= "Songkran festival day<br />";}
			elseif ( $month == "05" && $date == "01" ) {$output .= "National labour day<br />";}
			elseif ( $month == "05" && $date == "05" ) {$output .= "Coronation day<br />";}
			elseif ( $month == "08" && $date == "12" ) {$output .= "H.M. The queen's birth day<br />";}
			elseif ( $month == "10" && $date == "23" ) {$output .= "Chulalongkorn memorial day<br />";}
			elseif ( $month == "12" && $date == "05" ) {$output .= "H.M. The king's birth day<br />";}
			elseif ( $month == "12" && $date == "10" ) {$output .= "Constitution day<br />";}
			elseif ( $month == "12" && $date == "31" ) {$output .= "New year's eve<br />";}
		}
		// add span at begin and end point
		if ( $output != null ) {
			$output .= "</span>";
			$output = "<br /><span class=\"special_day\">" . $output;
		}
		return $output;
	}// display_special_days


	private function display_start_table() {
		return "<table class=\"okv_calendar\">\n";
	}// display_start_table
	
	
	private function display_week_days() {
		$output = "<tr>\n";
		for ( $i=1; $i<=7; $i++) {
			$output .= "<td class=\"calendar_week_day\">" . $this->display_week_day_translate($i) . "</td>\n";
		}
		$output .= "</tr>\n";
		return $output;
	}// display_week_days
	
	
	private function display_week_day_translate($day = '') {
		if ( !is_numeric($day) ) {
			return "Invalid day value.";
		}
		if ( $this->language == "thai" ) {
			$th_day = array("อา.", "จ.", "อ.", "พ.", "พฤ.", "ศ.", "ส.");
			return (isset($th_day[intval($day-1)]) ? $th_day[intval($day-1)] : "?day?");
		} else {
			$en_day = array("S", "M", "T", "W", "T", "F", "S");
			return (isset($en_day[intval($day-1)]) ? $en_day[intval($day-1)] : "?day?");
		}
	}// display_week_day_translated
	
	
	private function display_year_month() {
		$output = "<tr>\n";
		$output .= "<td class=\"prev_year\"><a href=\"" . $this->page_url."get_year=".($this->get_year-1)."&get_month=".$this->get_month."&get_date=".$this->get_date . "\">&laquo;</a></td>\n";
		$output .= "<td class=\"prev_month\"><a href=\"" . $this->display_get_prev_month() . "\">&lsaquo;</td>\n";
		$output .= "<td class=\"month_year\">" . $this->display_month_name()." ".$this->display_year_translated() . "</td>\n";
		$output .= "<td class=\"next_month\"><a href=\"" . $this->display_get_next_month() . "\">&rsaquo;</a></td>\n";
		$output .= "<td class=\"next_year\"><a href=\"" . $this->page_url."get_year=".($this->get_year+1)."&get_month=".$this->get_month."&get_date=".$this->get_date . "\">&raquo;</a></td>\n";
		$output .= "</tr>\n";
		return $output;
	}// display_year_month
	

	/**
	 * display_year_translated
	 * @return int
	 */
	private function display_year_translated() {
		if ( checkdate($this->get_month, $this->get_date, $this->get_year) ) {
			if ( $this->year_be == true ) {
				return ($this->get_year+543);
			} else {
				return $this->get_year;
			}
		}
	}// display_year_translated
	
	
	private function get_current_date($date = '') {
		if ( $this->get_year."-".$this->get_month."-".$date == date("Y-m-j") ) {
			return " current_date";
		}
	}// get_current_date
	
	
	private function get_current_click_date($date_check = '') {
		if ( !is_numeric($date_check) ) {return '';}
		$click_date = (isset($_GET['click_date']) ? trim(strip_tags($_GET['click_date'])) : "");
		$c_year = date("Y", strtotime($click_date));
		$c_month = date("m", strtotime($click_date));
		$c_date = date("d", strtotime($click_date));
		if ( $this->get_year == $c_year && $this->get_month == $c_month && $date_check == $c_date ) {
			return "click_date";
		}
	}


	
}

?>