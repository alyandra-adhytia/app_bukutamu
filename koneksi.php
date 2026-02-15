<?php
define('HOST_NAME', 'localhost');
define('USER_NAME', 'root');
define('PASSWORD', '');
define('DB_NAME', 'app_bukutamu');
define('DB_PORT', 3306);

$koneksi = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME, DB_PORT);
if (!$koneksi) {
    die('MySQL connection error: ' . mysqli_connect_error());
}

?>