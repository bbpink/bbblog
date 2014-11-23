<?php

include_once("../models/MenuDisplayable.trait.php");

class Article extends Model {

    use MenuDisplayable;

    //constructor
    public function __construct(){
        parent::__construct();
    }

    public function index($parameter) {
        $id = $parameter["get"]["id"];

        $a = $this->db->findById("articles", $id);
        $article = array_map(function($v) {
            $r = $v;
            $r["body"] = U::convertBreak($v["body"]);
            return $r;
        }, $a);

        foreach ($article[0] as $k=>$v)
            $this->$k = $v;

        //menu
        $this->recentTitles = $this->getRecentTitles();
        $this->backNumbers = $this->getBackNumbers();

        return $this;
    }

    public function admin($parameter) {
        $this->newsList = $this->db->query("select * from news order by id desc");
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
                                           ,"message"=>$parameter["post"]["message"]
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
