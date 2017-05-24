<?php
  class NewsEntry {

    public $id;
    public $title;
    public $imagePath;
    public $description;

    public static $savePath = "templates/newsdata.json";

    public static function getEntryList() {
      $entrys = array();
      $jsonArray = json_decode(file_get_contents(self::$savePath), true);

      foreach($jsonArray as $k => $v) {
        $entry = new NewsEntry();
        $entry->id = $k;
        $entry->title = $v["title"];
        $entry->imagePath = $v["path"];
        $entry->description = $v["description"];
        array_push($entrys, $entry);
      }

      return $entrys;
    }

    public static function getEntry($id) {
      foreach(self::getEntryList() as $e) {
        if($e->id == $id) {
          return $e;
        }
      }
      return null;
    }

    public static function getEntryArray() {
      return json_decode(file_get_contents(self::$savePath), true);
    }

    public static function getNextId() {
      $a = self::getEntryArray(self::$savePath);
      $string = self::generateRandomString(5);
      while(array_key_exists($string, $a)) {
        $string = self::generateRandomString(5);
      }
      return $string;
    }

    public function save() {
      echo self::$savePath;
      $arr = self::getEntryArray();
      $id = self::getNextId();
      $arr[$id] = array (
        "title" => $this->title,
        "path" => $this->imagePath,
        "description" => $this->description
      );

      file_put_contents(self::$savePath, json_encode($arr));

    }

    public function edit() {

    }

    public function delete() {

    }

    static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


  }
 ?>
