<?php
session_start();
if(isset($_SESSION["username"])){
	
session_destroy();
header("Location: login_form.php");
exit();
}