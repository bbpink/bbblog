<?php

class Rss extends Model {

    //constructor
    public function __construct(){
        parent::__construct();
        $this->useTemplateEngine = false;
    }

    public function index() {
        $core = new Core();

        //get articles
        $r = $this->db->query("select * from articles order by created desc limit 15");

        //create rss
        $root = '<?xml version="1.0" encoding="utf-8" ?><rss version="2.0"></rss>';
        $sxe = new SimpleXMLElement($root);
        $channel = $sxe->addChild("channel");
        $channel->addChild("title", $core->title);
        $channel->addChild("link", $core->url);
        $channel->addChild("description");
        $channel->addChild("language", "ja");
        $channel->addChild("copyright");
        $channel->addChild("lastBuildDate", date("D, d M Y H:i:s O", strtotime($r[0]["created"])));
        $channel->addChild("generator");
        $channel->addChild("docs", "http://blogs.law.harvard.edu/tech/rss");
        foreach ($r as $v) {
            $item = $channel->addChild("item");
            $item->addChild("title", $v["title"]);
            $description = str_replace("]]>", "]]&gt;", $v["body"]);
            $child = $item->addChild("description");
            if ($child !== NULL) {
                $node = dom_import_simplexml($child);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection(U::convertBreak($description)));
            }
            $item->addChild("link", $core->url . "/article/" . $v["id"]);
            $item->addChild("guid", $core->url . "/article/" . $v["id"])->addAttribute("isPermaLink", "true");
            $item->addChild("pubDate", date("D, d M Y H:i:s O", strtotime($v["created"])));
        }

        //out
        header("Content-type: application/rss+xml");
        $dom = dom_import_simplexml($sxe)->ownerDocument;
        $dom->formatOutput = true;
        return $dom->saveXML();
    }

    public function admin() {
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
