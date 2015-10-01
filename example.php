<?php
require_once "AS400DB.php";

	$db=new AS400DB("AS400","galileo","aer4halo");

	$result=$db->query("select * from mgart00f fetch first 100 rows only");
	
	if($result->num_rows){
	echo "$result->num_rows ROWS RETURNED!\n";
		while($line=$result->fetch_array()){
			print_r($line);
		}
	}
	else echo $db->ODBCerror;


?>
