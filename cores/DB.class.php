<?php

class DB {

    private $pdo;
    private static $me;

    //constructor
    public function __construct($dbpath){
        try {
            $this->pdo = new PDO("sqlite:" . $dbpath);
        } catch (PDOException $e){
            die($e->getMessage());
        }
    }

    //returns singleton object
    public static function getInstance() {
        if (!is_object(self::$me)) {
            self::$me = new DB(U::conf("db_path"));
        }
        return self::$me;
    }

    //execute
    public function execute($sql, $parameters=[]) {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);
    }

    //query
    public function query($sql, $parameters=[]) {
        $statement = $this->pdo->prepare($sql);
        if ($statement) {
            $statement->execute($parameters);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    //insert
    public function insert($table, $values=[]) {
        $sql = "INSERT INTO $table VALUES( null,";
        $q = array_fill(0, count($values), "?");
        $sql .= implode(",", $q) . ")";
        $this->execute($sql, $values);
        return $this->pdo->lastInsertId();
    }

    //update
    public function update($table, $values=[], $id) {
        if ($id == null) return null;
        $sql = "UPDATE $table SET ";
        
	$updateKeys = "";
        $updateValues = array();
	foreach($values as $k=>$v) {
            $updateKeys[] = " $k = ? ";
	    $updateValues[] = $v;
	}

        $sql .= implode(",", $updateKeys) . " WHERE id = ?";
        $updateValues[] = $id;
        $this->execute($sql, $updateValues);
        return $this->pdo->lastInsertId();
    }

    //get neighbourfood
    public function findNeighbours($table, $field, $current) {
        $sql = "SELECT *
                FROM   $table
                WHERE  $field > ?
                ORDER BY $field ASC
                LIMIT  1";
        @list($next) = $this->query($sql, array($current));

        $sql = "SELECT *
                FROM   $table
                WHERE  $field < ?
                ORDER BY $field DESC
                LIMIT  1";
        @list($prev) = $this->query($sql, array($current));

        count($next)==0 ? $next=null : $next;
        count($prev)==0 ? $prev=null : $prev;
        return array("next"=>$next, "prev"=>$prev);
    }

    public function __call($name, $args) {
        switch (true) {
            case preg_match("/^findBy/", $name): //todo: add limitation
                $targetField = str_replace("findBy", "", $name);
                $field = strtolower($targetField[0]).substr($targetField, 1);
                $sql = "SELECT *
                        FROM   $args[0]
                        WHERE  $field = ?";
                return $this->query($sql, array($args[1]));

            case preg_match("/^countBy/", $name):
                $targetField = str_replace("countBy", "", $name);
                $field = strtolower($targetField[0]).substr($targetField, 1);
                $sql = "SELECT count(*) as count
                        FROM   $args[0]
                        WHERE  $field = ?";
                $res = $this->query($sql, array($args[1]));
                return $res[0]["count"];

	    case preg_match("/^deleteBy/", $name):
	        $where = str_replace("deleteBy", "", $name);
	        $field = strtolower($where[0]).substr($where, 1);
                $sql = "DELETE
                        FROM   $args[0]
                        WHERE  $field = ?";
                $res = $this->execute($sql, array($args[1]));
                return $res;

            default:
                break;
        }
    }

}

?>
