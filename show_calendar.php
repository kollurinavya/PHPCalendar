<?php
	
error_reporting(0);
//connection to sql
session_start();
include ("SQLConnect.php");


	function onLoad(){
		$day = $_POST['showday'];
		$smonth = $_POST['showmonth'];
		$syear = $_POST['showyear'];
		$userid = $_SESSION['userid'];
		calendarHeader($userid,$day,$smonth,$syear);
		showCalendar($userid,$day,$smonth,$syear);
		
		
		echo '</div>';
	}
	if($_POST['action'] == 'prevmonth'){
		$day = 0;
		$smonth = $_POST['showmonth'];
		$syear = $_POST['showyear'];
		prevMonth($day,$smonth,$syear);
	}
	if($_POST['action'] == 'nextmonth'){
		$day = 0;
		echo $userid;
		$smonth = $_POST['showmonth'];
		$syear = $_POST['showyear'];
		nextMonth($day,$smonth,$syear);
	}
	if($_POST['action'] == 'userInput'){
		$smonth = $_POST['showmonth'];
		$syear = $_POST['showyear'];
		$sday = $_POST['showday'];

		$userid = $_SESSION['userid'];
		calendarHeader($userid,$day,$smonth,$syear);
		showCalendar($userid,$day,$smonth,$syear);
		
	}
	if($_POST['action'] == 'nextyear'){
		$day = 0;
		$smonth = $_POST['showmonth'];
		$syear = $_POST['showyear'];
		nextMonth($day,$smonth,$syear);
	}
	if($_POST['action'] == 'prevyear'){
		$day = 0;
		$smonth = $_POST['showmonth'];
		$syear = $_POST['showyear'];
		nextMonth($day,$smonth,$syear);
	}

	function prevMonth($day,$smonth,$syear){
		calendarHeader($userid,$day,$smonth,$syear);
		
		$userid = $_SESSION['userid'];
		showCalendar($userid,$day,$smonth,$syear);
		echo '</div>';	
	}
	function nextMonth($day,$smonth,$syear){
		calendarHeader($userid,$day,$smonth,$syear);
		
		$userid = $_SESSION['userid'];
		showCalendar($userid,$day,$smonth,$syear);
		echo '</div>';	
	}


	function calendarHeader($userid,$day,$smonth,$syear){
		echo '<div id = "calendar_wrap">';
		echo '<div class ="title_bar">';
		echo 'Select day: ';
		$start_day = 1;
		$end_day = 31;
		echo "<select class ='selectStyle' name ='days' id = 'days'>";

			for($i=$start_day;$i<=$end_day;$i++){
			if($day==$i){
			echo "<option value='$i' selected>$i</option>";
			}else{
				echo "<option value='$i'>$i</option>";
			}
		}
		echo "</select >";
		echo "   Select Month: ";
		echo "<select class ='selectStyle' name = 'month' id = 'month'>";
		for($i=1;$i<=12;$i++)
		{

			$date_Object   = DateTime::createFromFormat('!m', $i);
			$monthName = $date_Object->format('F');
			if($smonth==$i){
			echo "<option value=$i selected>$monthName</option>";
			$month_val=$i;
			}else{
				echo "<option value=$i>$monthName</option>";
			}

		}
		echo "</select>";

		$start_year = 1950;
    	$end_year = 2050;
   		 echo "   Select Year: ";
   			 echo "<select class ='selectStyle' name ='year' id ='year'>";
    	for($i=$start_year;$i<=$end_year;$i++){
			if($syear==$i){
			echo "<option value='$i' selected>$i</option>";
			}else{
				echo "<option value='$i'>$i</option>";
			}
		}
		echo "</select>";
		echo "   ";
	echo '<input class = "userInput" id = "userInput" name ="userInput" type ="button" onclick="javascript:addEvent('.$userid.','.$smonth.','.$day.','.$syear.');" value ="Add Event"/>';
		echo '</div>';
		
		echo '<div class ="title_bar">';
		echo '<div class ="prev_year"><input class = "Ybutton" type ="button" onclick="javascript:prevyear();" value="<<"/></div>';
		echo '<div class ="prev_month"><input class = "Mbutton" type ="button" onclick="javascript:prevmonth();" value="<"/></div>';
		echo '<div class ="show_month">';
		if($smonth == 1){ echo 'January'; }
		if($smonth == 2){ echo 'February';}
		if($smonth == 3){ echo 'March'; }
		if($smonth == 4){ echo 'April'; }
		if($smonth == 5){ echo 'May'; }
		if($smonth == 6){ echo 'June'; }
		if($smonth == 7){ echo 'July'; }
		if($smonth == 8){ echo 'August'; }
		if($smonth == 9){ echo 'September'; }
		if($smonth == 10){ echo 'October'; }
		if($smonth == 11){ echo 'November'; }
		if($smonth == 12){ echo 'December'; }
		echo ' ,  ';
		echo $syear;
		echo '</div>';
		echo '<div class = "next_month"><input class = "Mbutton" type ="button" onclick="javascript:nextmonth();" value =">"/> </div>';
		echo '<div class = "next_year"><input class = "Ybutton" type ="button" onclick="javascript:nextyear();" value =">>"/> </div>';
		echo '</div>';
		echo '<div class ="days">';
		echo '<div class="name_of_days">Sunday</div>';
		echo '<div class="name_of_days">Monday</div>';
		echo '<div class="name_of_days">Tuesday</div>';
		echo '<div class="name_of_days">Wednesday</div>';
		echo '<div class="name_of_days">Thursday</div>';
		echo '<div class="name_of_days">Friday</div>';
		echo '<div class="name_of_days">Saturday</div>';
		echo '<div class ="clear"></div>';
		echo '</div>';
	}

	function showCalendar($userid,$day,$smonth,$syear){

		$day_count = cal_days_in_month(CAL_GREGORIAN, $smonth, $syear);
		$prev_days = date('w',mktime(0,0,0,$smonth,1,$syear));
		$post_days = (6-(date('w',mktime(0,0,0,$smonth,$day_count,$syear))));

		if($prev_days != 0){
			for($i=1;$i<=$prev_days;$i++){
				echo '<div class ="non_cal_days"></div>';
			}
		}
		for($i=1;$i<=$day_count;$i++){
			//Events logic
			$date = $smonth.'/'.$i.'/'.$syear;
			$q = mysql_query('Select * from events where EventDate = "'.$date.'" and user_id = '.$userid.'');
			$rows = mysql_num_rows($q);
			if($rows >0){
				$e = "<a class = 'show_event' href = 'javascript:showEventDetails(".$userid.",".$smonth.",".$i.",".$syear.");' id = ".$date." name = '$date' >Show Event</a>";
			}

			/*$addEvent = "<a class = 'add_event' href = 'javascript:AddEvent(".$smonth.",".$i.",".$syear.");' id = ".$date." name = '$date' >Add Event</a>";
*/			if($i != $day){
			echo '<div class ="calendar_day">';
			echo '<div class ="day_heading">'.$i.'</div>';	
			}	
			if($i==$day){
			echo '<div class ="pcalendar_day">';
			echo '<div class ="day_heading">'.$i.'</div>';	
			
			}

			if($rows != 0){
				echo "<div class = 'event_style'> <br/> ".$e."</div>";

			}
			echo '</div>';
		}
		if($post_days != 0){
			for($i=1;$i<=$post_days;$i++){
				echo '<div class ="non_cal_days"></div>';
			}
		}

	}
	if($_POST['action'] == 'onload'){
		onLoad();
	}

	

	
?>