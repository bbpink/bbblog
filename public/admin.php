<?php

include_once("../cores/loader.php");

class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
    }
}

$me = new AdminController();
$core = new Core();
$me->filter = [new Security([
    "logout"=>"Location: " . $core->url . "/admin.php"
  , "onsession"=>"Location: " . $core->url . "/admin.php?m=top"
  , "badrequest"=>"Location: " . $core->url . "/admin.php"
])];
$me->action();
$me->display( array_key_exists("m", $_GET) ? "" : "login.tpl" );

?>
