<?php

include_once("../cores/loader.php");

class IndexController extends Controller {
    public function __construct() {
        parent::__construct();
    }
}

$me = new IndexController();
$me->action();
$me->display();

?>
