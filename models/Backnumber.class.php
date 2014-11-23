<?php

include_once("../models/MenuDisplayable.trait.php");

class Backnumber extends Model {

    use MenuDisplayable;

    //constructor
    public function __construct(){
        parent::__construct();
    }

    public function index($parameter) {

        //check parameters
        if ( !(array_key_exists("get", $parameter) && array_key_exists("month", $parameter["get"]) ) ) {
            return $this;
        }
        $yyyymm = $parameter["get"]["month"];
        if (preg_match("/^[0-9][0-9][0-9][0-9][0-9][0-9]$/", $yyyymm) !== 1) {
            return $this;
        }

        //get articles
        $timeCondition = substr($yyyymm, 0, 4) . "-" . substr($yyyymm, 4, 2) . "*";
        $r = $this->db->query("select * from articles where created glob ? order by created desc", [$timeCondition]);
        $this->articles = array_map(function($article) {
            $rec = $article;
            $rec["body"] = U::convertBreak($article["body"]);
            return $rec;
        }, $r);

        //menu
        $this->recentTitles = $this->getRecentTitles();
        $this->backNumbers = $this->getBackNumbers();

        return $this;
    }

    public function admin($parameter) {

        //get backnumbers
        $offset = 0;
        if (array_key_exists("offset", $parameter["get"])) {
            $offset = $parameter["get"]["offset"];
        }
        $this->backnumbers = $this->db->query("select * from articles order by id desc limit 20 offset ?", [$offset]);

        if (array_key_exists("offset", $parameter["get"])) {
            $this->useTemplateEngine = false;
            U::sendHeader(200, "Content-Type: text/html");
            echo json_encode($this->backnumbers);
            exit(0);
        }

        return $this;
    }

    public function json($parameter) {
        $res = array();

        if (!isset($parameter["post"]["id"])) {
            //nothnig parameter

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
}

?>
