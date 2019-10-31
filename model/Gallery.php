<?php

require_once __DIR__."/DBConnection.php";

Class Gallery
{  
    private $dbh;
    public $pics;

    public function init()
    {
        $this->dbh = (new DBConnection())->con;
        $sth = $this->dbh->query("SELECT id, comments, love, date FROM pictures ORDER BY date DESC LIMIT 28");
        $this->pics = $sth->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        while (sizeof($this->pics) < 28)
        {
            array_push($this->pics, $this->pics[$i]);  
            $i++;
        }
    }

    public function load($offset)
    {
        $this->dbh = (new DBConnection())->con;
        $sth = $this->dbh->prepare("SELECT id, comments, love, date FROM pictures ORDER BY date DESC LIMIT 28 OFFSET :offset");
        $sth->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sth->execute();
        $this->pics = $sth->fetchAll(PDO::FETCH_ASSOC);
        while (sizeof($this->pics) < 28)
        {
            $more = 28 - sizeof($this->pics);
            $sth = $this->dbh->prepare("SELECT id, comments, love, date FROM pictures ORDER BY date DESC LIMIT :more");
            $sth->bindValue(':more', $more, PDO::PARAM_INT);
            $sth->execute();
            $complements = $sth->fetchAll(PDO::FETCH_ASSOC);
            foreach ($complements as $elem)
                array_push($this->pics, $elem);
        }
    }

    public function select($id, $offset)
    {
        $this->dbh = (new DBConnection())->con;
        $sth = $this->dbh->prepare("SELECT id, comments, love, date FROM pictures WHERE user_id = :user ORDER BY id DESC LIMIT 10 OFFSET :offset");
        $sth->bindValue(':user', $id);
        $sth->bindValue(':offset', intval($offset), PDO::PARAM_INT);
        $sth->execute();
        $this->pics = $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}