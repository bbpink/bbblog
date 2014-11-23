<?php

include_once("../models/MenuDisplayable.trait.php");

class Top extends Model {

    use MenuDisplayable;

    public $init;

    //constructor
    public function __construct(){
        parent::__construct();
        $this->init = false;
    }

    public function index($parameter) {
        $a = $this->db->query("select * from articles order by created desc limit 7");
        $this->articles = array_map(function($article) {
            $r = $article;
            $r["body"] = U::convertBreak($article["body"]);
            return $r;
        }, $a);

        //menu
        $this->recentTitles = $this->getRecentTitles();
        $this->backNumbers = $this->getBackNumbers();

        return $this;
    }

    public function admin($parameter) {

        //blog system existence check
        $r = $this->db->query("select * from sqlite_master where type='table' and name = ?", array("users"));
        if (count($r) == 0) {
            $this->initialize($parameter);
            $this->init = true;
            return $this;
        }

        if ( array_key_exists("id", $parameter["get"]) && $parameter["get"]["id"] !== "" ) {
            $r = $this->db->findById("articles", $parameter["get"]["id"]);
            $this->id = $r[0]["id"];
            $this->title = $r[0]["title"];
            $this->body = $r[0]["body"];

        } else if ( array_key_exists("title", $parameter["post"]) && array_key_exists("body", $parameter["post"])
          && ($parameter["post"]["title"] !== "") && ($parameter["post"]["body"] !== "") ) {

            $now = U::now();

            //update
            if ( array_key_exists("id", $parameter["post"]) && $parameter["post"]["id"] !== "" ) {
                $this->db->update("articles", ["title"=>$parameter["post"]["title"], "body"=>$parameter["post"]["body"], "updated"=>$now], $parameter["post"]["id"]);

            //insert
            } else {
                $id = $this->db->insert("articles", [$_SESSION["id"], 1, 1, $parameter["post"]["title"], $parameter["post"]["body"], $now, $now]);
            }

            $core = new Core();
            U::sendHeader(303, "Location: " . $core->url . "/admin.php?m=backnumber");
            exit(0);
        }

        return $this;
    }

    public function json($parameter) {
        $res = array();

        if (!isset($parameter["post"]["id"])) {
            //nothing parameter

        } else if (strpos($parameter["post"]["id"], "insert") !== false) {
            $id = $this->db->insert("news", array($parameter["post"]["openDate"]
                                                , $parameter["post"]["message"]
                                                , 0
                                                , 1
                                                , date("Y-m-d H:i:s")
                                   ));

            $res["openDate"] = $parameter["post"]["openDate"];
            $res["message"] = $parameter["post"]["message"];
            $res["id"] = $id;

        } else if (strpos($parameter["post"]["id"], "news") !== false) {
            $id = str_replace("news_", "", $parameter["post"]["id"]);
            $this->db->update("news", array("openDate"=>$parameter["post"]["openDate"]
                                          , "message"=>$parameter["post"]["message"]
                                          , "viewOrder"=>0
                                          , "isDisplay"=>1
                                          , "modifiedDate"=>date("Y-m-d H:i:s")
                             ), $id);

            $res["openDate"] = $parameter["post"]["openDate"];
            $res["message"] = $parameter["post"]["message"];
            $res["id"] = $id;

        } else if (strpos($parameter["post"]["id"], "delete") !== false) {
            $id = str_replace("delete_", "", $parameter["post"]["id"]);
            $this->db->deleteById("news", $id);
            $res["id"] = $id;
        }

        return $res;
    }

    private function initialize($p) {

        //parameter existence
        if ( !(array_key_exists("id", $p["post"])
            && array_key_exists("password", $p["post"])
            && array_key_exists("confirm", $p["post"])) ) {
            return;
        }

        //parameter length
        if ( (strlen($p["post"]["id"]) == 0)
          || (strlen($p["post"]["password"]) == 0)
          || (strlen($p["post"]["confirm"]) == 0) ) {
            return;
        }

        //password and confirm
        if (strcmp($p["post"]["password"], $p["post"]["confirm"]) != 0) {
            return;
        }

        //initialize database
$createusers = <<< eof
CREATE TABLE users (
    id       INTEGER PRIMARY KEY
  , name     TEXT    NOT NULL
  , password TEXT    NOT NULL
  , salt     TEXT    NOT NULL
  , created  TEXT    NOT NULL
  , updated  TEXT    NOT NULL
);
eof;

$createarticles = <<< eof
CREATE TABLE articles (
    id           INTEGER PRIMARY KEY
  , uid          INTEGER NOT NULL
  , public       INTEGER NOT NULL
  , break        INTEGER NOT NULL
  , title        TEXT    NOT NULL
  , body         TEXT    NOT NULL
  , created      TEXT    NOT NULL
  , updated      TEXT    NOT NULL
);
eof;

        $createindex = "CREATE INDEX index_articles_created ON articles(created);";
        $this->db->execute($createusers);
        $this->db->execute($createarticles);
        $this->db->execute($createindex);

        //create user data
        $now = U::now();
        $salt = uniqid(rand());
        $password = Security::encryption($p["post"]["password"], $now, $salt);
        $id = $this->db->insert("users", [$p["post"]["id"], $password, $salt, $now, $now]);

        //redirect to login page
        $core = new Core();
        U::sendHeader(303, "Location: " . $core->url . "/admin.php");
        exit(0);
    }
}

?>
