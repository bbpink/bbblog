<?php

//base class(Model)
abstract class Model {
    public $title;
    public $processTime;
    public $url;
    public $useTemplateEngine;
    protected $db;

    //constructor
    public function __construct() {
        $this->db = DB::getInstance();
        $this->useTemplateEngine = true;
    }

}

?>
