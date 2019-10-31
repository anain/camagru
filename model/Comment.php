<?php

require_once __DIR__."/DBConnection.php";
require_once __DIR__."/PictureDescription.php";

Class Comment
{  
    private $dbh;
    public $userId;
    public $content;
    public $target;
    public $username;

    public function __construct($userId, $username, $content, $target)
    {
        $this->userId = $userId;
        $this->content = $content;
        $this->target = $target;
        $this->username = $username;
    }

    public function sendToDb()
    {
        $this->dbh = (new DBConnection())->con;
        $sth = $this->dbh->prepare("INSERT INTO comments(user_id, content, img_id) VALUES(:user_id, :content, :img_id)");
        $this->dbh->beginTransaction();
        $sth->execute(array('user_id' => $this->userId, 'content' => $this->content, 'img_id' => $this->target));
        $this->dbh->commit();
        $this->sendMail();
        (new PictureDescription($this->target))->updateCommentsNumber();   
    }

    public function sendMail()
    {
        $this->dbh = (new DBConnection())->con;
        $content = $this->username.' commented your picture.';
        $sql = "SELECT users.mail, users.notifications FROM users LEFT JOIN pictures ON pictures.user_id = users.id WHERE pictures.id = ?";
        $sth = $this->dbh->prepare($sql);
        $this->dbh->beginTransaction();
        if ($sth->execute(array($this->target))) 
            $res = $sth->fetch(PDO::FETCH_ASSOC);
        if (!($res['notifications']))   
            return;
        mail($res['mail'], "You have a new comment !" , $content, "From: camagru@camagru.com");
    }
}