<?php
error_reporting(0);
	if($_POST['action'] == 'showEvent'){
		$date = $_POST['eventId'];
		$userid = $_POST['userid'];
		showEvent($date,$userid);

	}

	if($_POST['action'] == 'deleteEvent'){
		$eventid = $_POST['eventId'];
		$month = $_POST['month'];
		$year = $_POST['year'];
		$day = $_POST['day'];
		$userid = $_POST['userid'];
		deleteEvent($eventid,$month,$day,$year,$userid);

	}
		if($_POST['action'] == 'updateEvent'){
		$eventid = $_POST['eventId'];
		$month = $_POST['month'];
		$year = $_POST['year'];
		$day = $_POST['day'];
		$userid = $_POST['userid'];
		$description = $_POST['description'];
		updateEvent($eventid,$month,$day,$year,$userid,$description);

	}
	
	if($_POST['action'] == 'addEvent'){
		$date =  $_POST['edate'];
		$userid = $_POST['userid'];
		$description = $_POST['description'];
		addEvent($date,$userid,$description);
	}

	if($_POST['action'] == 'addUser'){
		$username = $_POST['username'];
		$password = $_POST['password'];
		addUser($username,$password);
	}

	function addUser($username,$password){
		include('SQLConnect.php');
		$q = mysql_query("select max(ID) as maxid from user_info");
		if(mysql_num_rows($q) >0){
			while ($ans = mysql_fetch_assoc($q)) {
				$id = $ans['maxid']+1;
			}
		}
		$q = mysql_query("select userName from user_info where userName = '".$username."'");
		if(mysql_num_rows($q) >0){
			echo "userexists";

		}else if( mysql_query('Insert into user_info(ID,userName,password) values ('.$id.',"'.$username.'","'.$password.'")')){
			echo "addsuccess";
		}else {
			echo "addfail";
		}

	}
	
	function showEvent($date,$userid){
		include('SQLConnect.php');
		$sevents = '';
		$que = mysql_query('select description,Event_id from events where EventDate = "'.$date.'" and user_id ="'.$userid.'"') ;
		$rows = mysql_num_rows($que);
		$arr = explode('/',$date);
		$month =$arr[0];
		$day =$arr[1];
		$year = $arr[2];
		if($rows >0){
			$sevents .= '<div id ="eventsControl"><button class ="Addbutton" onMouseDown = "overlay()">Close</button><br/><br/><b>'.$date.'</b><br/><br/></div>';
			$count =0;
			while($row = mysql_fetch_array($que)) {
				$desc = $row['description'];
				$event_id = $row['Event_id'];
				$sevents .= '<div id = "eventBody">'.$desc.'<br/>
				<div class = "updateEvent" id ='.$count.'> 
				<label id ="label">Update Event and start time:</label>
			<textarea name = "addEvent" id='.$count.$count.'> </textarea>
			<button class ="Addbutton"onMouseDown="javascript:UpdateEvent('.$event_id.','.$month.','.$day.','.$year.','.$userid.','.$count.');" id = "'.$date.'">Update</button>
			                   <br/>
			    </div>
			     <button class ="Addbutton"onMouseDown="javascript:showUpdate('.$count.',this);" id = "'.$date.'">Update Event</button>
				<button class ="Addbutton" onMouseDown="javascript:deleteEvent('.$event_id.','.$month.','.$day.','.$year.','.$userid.');" id = "'.$date.'">Delete Event</button>  <hr><br/> </div>';
				$count += 1;
			} 
		}
		echo $sevents;	
	}

	function addEvent($date,$userid,$description){
			include('SQLConnect.php');
		//find max id 
		$q = mysql_query("select max(id) as maxid from events");
		if(mysql_num_rows($q) >0){
			while ($ans = mysql_fetch_assoc($q)) {
				$id = $ans['maxid']+1;
			}
		}
		$x = 'select max(Event_id) as maxeid from events where userid= '.$userid.'';
		$q1 = mysql_query('select max(Event_id) as maxeid from events where user_id= '.$userid.'');
		if(mysql_num_rows($q1) >0){
			while ($ans1 = mysql_fetch_assoc($q1)) {
				$eid = $ans1['maxeid']+1;
			}
		}
		//echo $date;
		if( mysql_query('Insert into events(id,EventDate,user_id,Event_id,Description) values ('.$id.',"'.$date.'",'.$userid.','.$eid.',"'.$description.'")')){
			echo "addsuccess";
		}else {
			echo "addfail";
		}
		
	}

	function deleteEvent($eventid,$month,$day,$year,$userid){
		include('SQLConnect.php');
		$date = $month.'/'.$day.'/'.$year;
		$query = 'delete from events where user_id ='.$userid.' and Event_id ='.$eventid.' and EventDate ="'.$date.'" ';
		$q = mysql_query('delete from events where user_id ='.$userid.' and Event_id ='.$eventid.' and EventDate ="'.$date.'" ');
		echo $query;
		if($q){
			echo 'deleteSuccess';
		}else{
			echo 'deleteFail';
		}
		//echo $query; 
	}

	function updateEvent($eventid,$month,$day,$year,$userid,$description){
		include('SQLConnect.php');
		$date = $month.'/'.$day.'/'.$year;
		$query = 'update events set description = "'.$description.'" where EventDate = "'.$date.'" and Event_id ='.$eventid.' and user_id ='.$userid.' ' ;
		$update = mysql_query($query);
		if($update){
			echo 'updateSuccess';
		}else{
			echo 'updatefail';
		}
	}
?>