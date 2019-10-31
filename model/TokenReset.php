<?php

require_once __DIR__."/DBConnection.php";
require_once __DIR__."/Token.php";

date_default_timezone_set('Europe/Paris');

Class TokenReset extends Token
{
    public $user;

    public function __construct($user)
    { 
        $this->user = $user;
    }

    public function register()
    {
       try 
       {
            $this->dbh = (new DBConnection())->con;
            $sth = $this->dbh->prepare("SELECT id, mail, username FROM users WHERE username = ?");
            $sth->execute(array($this->user->username));; 
            if ($sth === false)
                return;
            $res = $sth->fetchall(PDO::FETCH_ASSOC)[0];
            {
                $this->user->email= $res['mail'];
                $this->user->id = $res['id'];
            }
            $stmt = $this->dbh->prepare("INSERT INTO reset_tokens(token, username, creation_date, expiration_date) VALUES(:token, :username, :creation_date, :expiration_date)");
            $this->dbh->beginTransaction();
            $stmt->execute(array('token' => $this->token, 'username' => $this->user->username, 'creation_date' => $this->creationDate, 'expiration_date' => $this->expirationDate));  
            $this->dbh->commit();
        }
        catch (PDOException $e)
        {
            $this->dbh->rollback();
            throw new Exception($e);
        }
    }

    public function sendMail()
    {
        $content = "Click on the link to reset your password : "."http://127.0.0.1:8080?page=reset&token=".$this->token;
        mail($this->user->email, "Reset your password" , $content, "From: camagru@camagru.com");
    }
}