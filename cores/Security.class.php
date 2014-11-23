<?php

class Security implements Filter {

    private $onsession;
    private $db;
    private $attr;

    //constructor
    public function __construct($attribute=[]) {
        $parameter = $_POST;
        $this->onsession = false;
        $this->db = DB::getInstance();
        $this->attr = $attribute;

        session_start();

        //if parameter(id and pass) exists, try to login
        if ( (array_key_exists("id", $parameter) && array_key_exists("password", $parameter))
          && ( ($parameter["id"] !== "") && ($parameter["password"] !== "") )
           ) {
            $this->login($parameter["id"], $parameter["password"]);
        }
    }

    public function filterAction() {

        if ($this->isOnSession()) {

            //logout action
            if ( array_key_exists("logout", $_POST) ) {
                $this->goodbyeSession();

                //redirect to logout page
                if ( array_key_exists("logout", $this->attr) ) {
                    U::sendHeader(303, $this->attr["logout"]);
                } else {
                    U::sendHeader(500);
                }
                exit();
            }

            //if mode parameter is nothing, redirect to onsession page
            if ( !array_key_exists("m", $_GET) ) {

                if ( array_key_exists("onsession", $this->attr) ) {
                    U::sendHeader(303, $this->attr["onsession"]);
                } else {
                    U::sendHeader(500);
                }
                exit();
            }

        //is not on session and have mode parameter (bad request)
        } else if ( array_key_exists("m", $_GET) ) {

            if ( array_key_exists("badrequest", $this->attr) ) {
                U::sendHeader(303, $this->attr["badrequest"]);
            } else {
                U::sendHeader(500);
            }
            exit();
        }

    }

    private function login($id, $password) {

        //authentication
        $user = $this->db->findByName("users", $id);
        if (count($user) != 0) {

            $in = Security::encryption($password, $user[0]["created"], $user[0]["salt"]);
            if ( strcmp($in, $user[0]["password"]) == 0 ) {
                $this->createSession();
                $this->onsession = true;
                $_SESSION["id"] = $user[0]["id"];

                //authentication succeeded
                return;
            }
        }

        $this->goodbyeSession();
        return;
    }

    public function isOnSession() {
        if ( array_key_exists("initiated", $_SESSION) && array_key_exists(session_name(), $_COOKIE) ) {
            $this->onsession = true;
        }
        return $this->onsession;
    }

    private function createSession() {
        if ( !array_key_exists("initiated", $_SESSION) ) {
            session_regenerate_id(true);
            $_SESSION["initiated"] = true;
        }
    }

    public function goodbyeSession() {
        $_SESSION = [];
        setcookie(session_name(), '', time()-42000, '/');
        @session_destroy();
    }

    public static function encryption($password, $salt1, $salt2="") {
        $result = $salt1 . $salt2 . $password;
        $algorithm = "sha512";

        for ($i=0; $i < 1000; $i++) {
            switch ($i % 6) {
                case 0:
                    $result = hash($algorithm, $salt1 . $salt2 . $result);
                    break;
                case 1:
                    $result = hash($algorithm, $salt2 . $salt1 . $result);
                    break;
                case 2:
                    $result = hash($algorithm, $salt1 . $result . $salt2);
                    break;
                case 3:
                    $result = hash($algorithm, $salt2 . $result . $salt1);
                    break;
                case 4:
                    $result = hash($algorithm, $result . $salt1 . $salt2);
                    break;
                case 5:
                    $result = hash($algorithm, $result . $salt2 . $salt1);
                    break;
            }
        }

        return $result;
    }

}

?>
