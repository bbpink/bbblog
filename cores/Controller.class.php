<?php

//base class(Controller)
abstract class Controller {
    private $entrance;
    private $twig;
    private $operation;
    public $modelObj;
    public $filter;
    private $core;

    //constructor
    public function __construct() {
        U::setConfig();
        $this->entrance = strtolower(str_replace("Controller", "", get_class($this)));
        $this->filter = [];
    }

    //render on Twig (or response something)
    public function display($target="") {
        $ent = $this->entrance;

        if ($this->modelObj->useTemplateEngine) {
            if (!is_object($this->twig)) $this->setTwig();
            $template = $this->twig->loadTemplate(($target === "") ? $this->operation . ".$ent.tpl" : $target);
            echo $template->render(array("model"=>$this->modelObj->$ent(array("get"=>$_GET, "post"=>$_POST)), "core"=>new Core()));
        } else {
            echo $this->modelObj->$ent();
        }
    }

    //twig setting(private)
    private function setTwig() {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(U::conf("template_dir"));
        $this->twig = new Twig_Environment($loader,array("cache"=>U::conf("template_cache"),"auto_reload"=>true, "debug"=>(!U::conf("production"))));
        $escaper = new Twig_Extension_Escaper(true);
        $this->twig->addExtension($escaper);
        if (!U::conf("production")) {
            $this->twig->addExtension(new Twig_Extension_Debug());
        }
    }

    //create model object
    public function action() {
        $this->filtering();

        $this->operation = isset($_GET["m"]) ? $_GET["m"] : "top";
        $models = scandir("../models/");

        if (in_array(ucfirst($this->operation) . ".class.php", $models)) {
            $classname = ucfirst($this->operation);
            include_once("../models/" . $classname . ".class.php");
            $this->modelObj = new $classname;

        } else {
            //don't exist class file
            U::dyingMessage(404);
        }
    }

    //filtering
    private function filtering() {
        if (count($this->filter) > 0) {
            foreach ($this->filter as $k=>$v) {
                $v->filterAction();
            }
        }
    }

}

class Core {
    public function __construct() {
        $env = ($_SERVER["SERVER_NAME"] === U::conf("production_host")) ? "production" : "development";
        $this->production = ($_SERVER["SERVER_NAME"] === U::conf("production_host"));
        $this->url = "http://" . U::conf($env . "_host");
        $this->title = U::conf($env . "_title");
        $this->year = date("Y");
        $this->contact = U::conf($env . "_contact");
    }
}

?>
