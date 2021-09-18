<?php
require_once("db.php");
$id = $_GET['delete'];

$sql = "DELETE FROM portfolio WHERE id = $id";
if (mysqli_query($connection, $sql)) {
    echo "Успешно удалено";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
}
?>
<br>
<a style="font-size: 36px; padding: 10px;" href="../admin.php">Готово</a>

