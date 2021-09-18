<?php
session_start();
error_reporting(0);
?>
<head>
    <title>WEDDINGS DECOR</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <!-- STYLESHEET CSS FILES -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nivo-lightbox.css">
    <link rel="stylesheet" href="css/nivo_themes/default/default.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap" rel="stylesheet">
</head>
<?php
require_once("./includes/db.php");
if (isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM `portfolio` WHERE `id` = $id";
    if (mysqli_query($connection, $delete_sql)) {
    } else {
        echo "Error: " . $delete_sql . "<br>" . mysqli_error($connection);
    }
}
if (isset($_GET['del'])){
    $id = $_GET['del'];
    $delete_sql = "DELETE FROM `images` WHERE `id` = $id";
    if (mysqli_query($connection, $delete_sql)) {
    } else {
        echo "Error: " . $delete_sql . "<br>" . mysqli_error($connection);
    }
}
$correct_password = 'nebodecor2132';
if (isset($_POST['password']) and $_POST['password'] == $correct_password) {
    $_SESSION['password'] = 'access';
}
if (isset($_SESSION['password']) and $_SESSION['password'] == "access") {
//Panel
$portfolio_list = mysqli_query($connection, "SELECT * FROM `portfolio`");
$category = $_GET['cat'];
$chosen_cat = mysqli_query($connection, "SELECT * FROM `portfolio` WHERE `id` = $category");
$chosen_cat = mysqli_fetch_assoc($chosen_cat);



$count = 9; // количество полей для загрузки файлов
$i = 0;
$path = 'photos/'; // путь до папки куда сохранять, ./ считать от расположениея скрипта
$path_img = 'photos/';

if (!is_dir($path)) {
    mkdir($path, 0777, true);
}
?>
    <h2>Выберите портфолио</h2>
    <?php
     while ($port = mysqli_fetch_assoc($portfolio_list)) {
    ?>
    <ul>
        <a class="main-cat" href="admin.php?cat=<?php echo($port['id']) ?>"><?php echo($port['name']) ?></a>
        <a class="main-cat" style="color: red; margin: 20px;" href="admin.php?delete=<?php echo($port['id']) ?>">Удалить</a>
    </ul>
<?php }?>
    <div class="add-pportfolio">
        <form action="includes/insert.php" method="post">
            Название: <input style="margin: 10px;" type="text" name="name">
            <input type="submit"/>
        </form>
    </div>
<?php
if (isset($chosen_cat)){
    $ID = $chosen_cat['id'];
    $photos = mysqli_query($connection, "SELECT * FROM `images` WHERE `portfolio_id` = $ID");
?>
        <h3 style="margin-left:30px; "><strong><?php echo($chosen_cat['name']) ?></strong></h3>
    <div class="row">
    <?php
    while ($photo = mysqli_fetch_assoc($photos)) {
        ?>
                <img src="<?php echo($photo['name']) ?>" height="100" width="100">
                <a href="includes/swap.php?id=<?php echo $photo['id']?>">Заменить</a>
                <a href="admin.php?del=<?php echo $photo['id']?>" style="color: red;">Удалить</a>
            <?php

    }
    ?>
    </div>
    <br>
    <h3 style="margin-left:30px; ">Добавление в портфолио <strong><?php echo($chosen_cat['name']) ?></strong></h3>
    <form action="" enctype="multipart/form-data" method="post">
        <?php while (++$i <= $count) : ?>
            <div style="padding: 3px;"><input type="file" name="file[]"/></div>
        <?php endwhile; ?>
        <div class="button" style="padding: 10px;"><input type="submit" name="submit" value="Добавить" style="padding: 15px; color: #5e5e5e"/></div>
    </form>

<?php
}
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
             $sql = "INSERT INTO images (portfolio_id, name)
        VALUES ('$category', '$image_name')";
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
}else{
?>
  <form method="POST">
   <p><input type="password" maxlength="25" name="password" class="passField"></p>
   <p><input type="submit" value="Войти"></p>
  </form>
  <?php
}
unset($_POST['file']);
mysqli_close($connection);
?>
