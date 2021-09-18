<?php
$connection = mysqli_connect('localhost', 'u101568_admin', 'aknebodecor2132', 'u101568_database');
mysqli_set_charset($connection, 'utf8');

if($connection == false)
{
	echo 'Не удалось подключиться к базе данных!<br>';
	echo mysqli_connect_error();
	exit();
}
?>