<?php
require_once("db.php");

$swap_id = $_GET['id'];

$photo = mysqli_query($connection, "SELECT * FROM `images` WHERE `id` = $swap_id");
$photo = mysqli_fetch_assoc($photo);
$path = '../photos/'; // путь до папки куда сохранять, ./ считать от расположениея скрипта
$path_img = 'photos/';
$old_name = $photo['name'];

$count = 3; // количество полей для загрузки файлов
$i = 0;
?>
<h3 style="margin-left:30px; ">Замена фото</h3>
<img src="../<?php echo $old_name?>" width="140" height="140">
<form action="" enctype="multipart/form-data" method="post">
        <div style="padding: 3px;"><input type="file" name="file[]"/></div>
    <div class="button" style="padding: 10px;"><input type="submit" name="submit" value="Заменить" style="padding: 15px; color: #5e5e5e"/></div>
</form>
<br>
<a style="font-size: 36px; padding: 10px;" href="../admin.php">Готово</a>
<?php
if (isset($_POST['submit']) && count($_FILES)) {
    for ($i = 0; $i <= $count; $i++) {
        $newnames = [];
        if (!empty($_FILES['file']['name'][$i])) {
            if ($info = getimagesize($_FILES['file']['tmp_name'][$i])) {
                $image = imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name'][$i]));
                $name = explode('.', $_FILES['file']['name'][$i]);
                // обработка и сохранение
                $newname = $path . time() . $name[0] . '.jpg'; // это имя для базы, даже путь полный, только имя можно получить например через basename();
                $image_name = $path_img . time() . $name[0] . '.jpg';
                $new = $newname;
                $sql = "UPDATE `images` SET `name`='$image_name' WHERE `id` = '$swap_id'";
                if (mysqli_query($connection, $sql)) {
                    echo "Успешно добавлено";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                }
                $newnames[] = $newname; // basename($name);
                imagepng($image, $newname, 9 , PNG_ALL_FILTERS);
            } else {
                echo '<h2>Выбран неверный файл ' . $_FILES['file']['name'][$i] . '</h2>';
            }
        } else {
            continue;
        }
        echo '<pre>' . print_r($newnames, true) . '</pre>';

    }



} else {
    echo '<h2>Ничего не выбрано</h2>';
}

