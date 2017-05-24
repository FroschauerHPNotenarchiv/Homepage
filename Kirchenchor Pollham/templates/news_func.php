<?php
  function get_news() {
    if(!empty($_FILES["inFile"]["tmp_name"])) {
      echo $_FILES["inFile"]["name"];
      $type = pathinfo($_FILES["inFile"]["name"], PATHINFO_EXTENSION);
      echo "   " . $type;
    }

  }

  function handle_file_upload() {
    $target_dir = "images/Events/";
$type = pathinfo($_FILES["inFile"]["name"], PATHINFO_EXTENSION);
$target_file = $target_dir . pathinfo($_FILES['inFile']['name'], PATHINFO_FILENAME);
$fileOk = 1;

// If there has been selected a file to upload
if(!empty($_FILES["inFile"]["tmp_name"])) {

  // Max File Size: 3 MB
  if($_FILES["inFile"]["size"] > 3000000) {
    $fileOk = 0;
  }

  if(getimagesize($_FILES["inFile"]["tmp_name"]) == false) {
    $fileOk = 0;
  }

  if($type !== "jpg" && $type !== "png" && $type !== "jpeg") {
    $fileOk = 0;
  }

  if($fileOk == 1) {
    if(file_exists($target_file . "." . $type)) {
      $i = 0;
      while(file_exists($target_file . "." .$type)) {
        $target_file = $target_file . $i;
      }
    }

    $uploaded = move_uploaded_file(
      $_FILES["inFile"]["tmp_name"], $target_file . "." . $type
    );

    if($uploaded) {
      return $target_file . "." . $type;
    } else {
      return null;
    }
  }

  return null;


}
  }
 ?>
