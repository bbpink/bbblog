<?php

class Css extends Model {

    //constructor
    public function __construct(){
        parent::__construct();
        $this->useTemplateEngine = false;
    }

    private function getStyle($entrance) {
        $rawfile = U::conf("stylesheet_dir") . "/" . $entrance . ".scss";

        //scss existence check
        if (file_exists($rawfile)) {

            $cachename = $entrance . ".scss_" . filemtime($rawfile);
            $cachepath = U::conf("stylesheet_cache") . "/" . $cachename;

            //cache existence check
            if (file_exists($cachepath)) {

                //Not Modified
                //todo: conditional GET request (responses Last-Modified)

            } else {

                //delete old caches
                foreach (glob(U::conf("stylesheet_cache") . "/" . $entrance . ".scss*") as $old) {
                    unlink($old);
                }

                //create cache
                $scss = new scssc();
                file_put_contents($cachepath, $scss->compile(file_get_contents($rawfile)));

            }

            //response
            U::sendHeader(200, "Content-Type: text/css");
            return file_get_contents($cachepath);

        } else {
            U::dyingMessage(404);
        }
    }

    public function index() {
        return $this->getStyle(__FUNCTION__);
    }

    public function admin() {
        return $this->getStyle(__FUNCTION__);
    }

    public function json($parameter) {
        $res = array();

        if (!isset($parameter["post"]["id"])) {
	} else if (strpos($parameter["post"]["id"], "insert") !== false) {
	    $id = $this->db->insert("news", array($parameter["post"]["openDate"]
                                            ,$parameter["post"]["message"]
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
				      )
			, $id);

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