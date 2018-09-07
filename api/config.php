<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ele";

    $conn = new mysqli($servername, $username, $password, $dbname);

    function textencode($str){
		$str = 	str_replace("'","",$str);
		$str = 	str_replace('"',"",$str);
		$str = 	str_replace(";","",$str);
		$str = 	str_replace("--","",$str);
		$str = 	str_replace("%","",$str);
		$str = 	str_replace("=","",$str);
		return $str;
	}

?>