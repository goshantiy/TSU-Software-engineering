<?php
include 'bdconnect.php';
include 'header.php';
if($_SESSION['role']!=3)
header('Location:/index.php');
$data=$_POST;

if(isset($data['btn_submit_song']))
{
  if(trim($data['form-author']) == '') {  
    $errors[] = "Введите автора!";
  }
  if (trim($data['form-release-name']) == '') {
        $errors[] = "Введите название песни!";
    }
    if(trim($data['form-release-name']) == '') {
        $errors[] = "Введите название релиза!";
    }
    if (!file_exists($_FILES['form-song']['tmp_name']) || !is_uploaded_file($_FILES['form-song']['tmp_name'])) 
    {
      $errors[] = "Выберите песню!";
    }
    if (empty($errors))
  {
    $author = strip_tags(htmlspecialchars($_POST["form-author"]));
    $uploaddir = "/music/author/".$author.'/';
    if (!file_exists("C:/xampp/htdocs/created".$uploaddir)) {
            mkdir("C:/xampp/htdocs/created" . $uploaddir, 0777, true);
        }
        $allowedTypes = array("audio/mpeg3","audio/x-mpeg-3","audio/mpeg");
    if (!in_array($_FILES["form-song"]["type"], $allowedTypes))
    {
      $errors[]="Ошибка неверный тип ".$_FILES["form-song"]["type"];
    }
    $songName=$_FILES['form-song']['name'];    
    if(empty($errors)&&!move_uploaded_file($_FILES["form-song"]["tmp_name"],"C:/xampp/htdocs/created".$uploaddir.$songName))
    {
    print_r($_FILES['form-song']['name']);
      $errors[]= "Ошибка move song";
    }


  $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка sql connection" . mysqli_error($link)); 
  $author = strip_tags(htmlspecialchars(mysqli_real_escape_string($link, $_POST['form-author'])));
  $release_name = strip_tags(htmlspecialchars(mysqli_real_escape_string($link,$_POST['form-release-name'])));
  $song_name = strip_tags(htmlspecialchars(mysqli_real_escape_string($link,$songName)));
    
     if(empty($errors))
    {
      $song_path=strip_tags(htmlspecialchars($uploaddir.$songName));
      $id=$_SESSION['user_id'];
      $query ="INSERT INTO content VALUES('$id', NULL ,'$author','$release_name','$song_name', 'default/default_cover.png', '$song_path')";
      	$result = mysqli_query($link, $query) or die("Ошибка sql query" . mysqli_error($link)); 
        if($result)
        {
          echo "<span style='color:green; display:flex; justify-content: center;'>Песня загружена</span>";
        }        
        mysqli_close($link);
    }
  else {echo '<div style="color: red; display:flex; justify-content: center; ">' . array_shift($errors). '</div><hr>';}
    }
  else echo '<div style="color: red; display:flex; justify-content: center; ">' . array_shift($errors). '</div><hr>';
}

?>

<!DOCTYPE html>
<html>
 <head>
 <link rel="stylesheet" href="/css/reg.css" type="text/css">
  <meta charset="utf-8">
  <title>Отправка файла на сервер</title>
 </head>
 <body>
     <div class="container positioner">
 <div class="center left">
 <form enctype="multipart/form-data" action="upload.php" method="post">
          <div class="col-6 form-group">
									<p>Author:</p>
									<input type="text" id="form-author" name="form-author" value=""  placeholder="Author"/>
								</div>
								<div class="col-6 form-group">
									<p>Release Name:</p>
									<input type="text" id="form-release-name" name="form-release-name" value=""  placeholder="Release name"/>
								</div>
              <div class="col-6 form-group">
                  Song:<br/><input type="file" name="form-song" accept=".mp3">
              </div>
              <div class="col-12 left">
									<button class="btn btn-dark m-0" id="btn_submit_song" name="btn_submit_song" value="Submit">Submit</button>
								</div>
  </form> 
</div>
</div>
 </body>
</html>