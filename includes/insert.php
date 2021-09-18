<?php
require_once("db.php");
$add_name = $_POST['name'];

$sql = "INSERT INTO portfolio (name)
VALUES ('$add_name')";

if (mysqli_query($connection, $sql)) {
    echo "Успешно добавлено";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
}
?>
<br>
<a style="font-size: 36px; padding: 10px;" href="../admin.php">Готово</a>

