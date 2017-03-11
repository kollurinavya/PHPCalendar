<?php 
error_reporting(0);
session_start();
$userName = $_POST['username'];
$password = $_POST['password'];

include('SQLConnect.php');
$query = mysql_query('select ID from user_info where userName = "'.$userName.'" and password= "'.$password.'"');
if(mysql_num_rows($query) >0){
	while($row = mysql_fetch_assoc($query)){
		$_SESSION['userid'] = $row["ID"];
	}
	echo "valid";
}else{
	echo "notright";
}


?>

<!-- if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
}  -->