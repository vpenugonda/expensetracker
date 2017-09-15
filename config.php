<?php
$server_name = "localhost";
$db_name = "expenses";
$user_name = "root";
$db_pswd = "123456";
$link = mysqli_connect($server_name, $user_name, $db_pswd, $db_name);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
if (!function_exists("dateForm2DB")) {
    function dateForm2DB($frm_date)
    {
        $frm_date = explode("/", $frm_date);
        if (!empty($frm_date[0]) && !empty($frm_date[1]) && !empty($frm_date[2])) {
            return $frm_date[2] . "-" . $frm_date[1] . "-" . $frm_date[0];
        } else {
            return "";
        }
    }
}


?>