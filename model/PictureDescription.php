<?php

require_once __DIR__."/DBConnection.php";

Class PictureDescription
{  
    private $dbh;
    public $picId;
    public $commentsList;

    public function __construct($picId)
    {
        $this->picId = $picId;
    }

    public function getComments()
    {
        $this->dbh = (new DBConnection())->con;
        $sql = "SELECT users.username, comments.content FROM comments LEFT JOIN users ON comments.user_id = users.id WHERE comments.img_id = ? ORDER BY comments.id";
        $sth = $this->dbh->prepare($sql);
        $this->dbh->beginTransaction();
        if ($sth->execute(array($this->picId))) 
            $this->commentsList = $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCommentsNumber()
    {
        $sql = "UPDATE pictures SET comments = comments + 1 WHERE id = ?";
        $this->dbh = (new DBConnection())->con;
        $sth = $this->dbh->prepare($sql);
        $this->dbh->beginTransaction();
        $sth->execute(array($this->picId));
        $this->dbh->commit();
    }

    public function updateLoveNumber()
    {
        $sql = "UPDATE pictures SET love = love + 1 WHERE id = ?";
        $this->dbh = (new DBConnection())->con;
        $sth = $this->dbh->prepare($sql);
        $this->dbh->beginTransaction();
        $sth->execute(array($this->picId));
        $this->dbh->commit();
    }

    public function delete()
    {
        $this->dbh = (new DBConnection())->con;
        $sql = "DELETE from pictures WHERE id = ?";
        $sth = $this->dbh->prepare($sql);
        $this->dbh->beginTransaction();
        $sth->execute(array($this->picId));
        $this->dbh->commit();
        $path =  __DIR__."/../static/gallery/".$this->picId.".png";
        unlink($path);
    }
}

// $path =  __DIR__."/test.png";
// echo $path;
// if( file_exists($path))
// $p = unlink($path);
// else
// echo 'notfound';
// echo $p;
// $add = '../static/gallery/132.png';
// @unlink($add);