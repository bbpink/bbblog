<?php

class U {

    private static $conf;

    public static function setConfig() {
        self::$conf = Spyc::YAMLLoad("../settings.yml");
    }

    public static function conf($p) {
        return self::$conf[$p];
    }

    //datetime convert
    static public function adjustDate($date) {
    }

    //send http header
    static public function sendHeader($code, $location="") {
        if ($location === "") {
            header("HTTP", true, $code);
        } else {
            header($location, true, $code);
        }
    }

    //exit with sending http header
    static public function dyingMessage($code, $location="") {
        self::sendHeader($code, $location);
        die();
    }

    static public function convertBreak($context) {
        $result = "";
        $unified = str_replace("\r\n", "\n", $context);
        $unified = str_replace("\r", "\n", $unified);

        $exploded = preg_split("/(\n)(\n)+/i", $unified);
        foreach ($exploded as $v) {
            $result .= "<p>" . preg_replace("/(\n)/i", "<br />", $v) . "</p>";
        }

        if ($result == "<p></p>") $result = "";
        return $result;
    }

    static public function now() {
        return date("Y-m-d H:i:s");
    }

}

?>
