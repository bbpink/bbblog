<?php

include_once("../cores/loader.php");

class CssController extends Controller {
    public function __construct() {
        parent::__construct();
    }
}

$me = new CssController();
$me->action();
$me->display();

?>
