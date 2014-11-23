<?php

trait MenuDisplayable {

    public function getRecentTitles() {
        return $this->db->query("select * from articles order by id desc limit 10");
    }

    public function getBackNumbers() {
        $result = [];
        $years = $this->db->query("select distinct strftime('%Y', created) as year from articles group by strftime('%Y', created) order by created desc");
        foreach ($years as $v) {
            $articlesByMonth = $this->db->query("select distinct strftime('%Y', created) as year, strftime('%m', created) as month, count(id) as articles from articles where created glob ? group by strftime('%Y', created), strftime('%m', created) order by created desc", array($v["year"] . "*"));
            $result[$v["year"]] = $articlesByMonth;
        }

        return $result;
    }
}

?>
