<?php

require_once __DIR__."/TokenConfirmation.php";
require_once __DIR__."/TokenReset.php";
require_once __DIR__."/DBConnection.php";

date_default_timezone_set('Europe/Paris');

Class User
{   
    public $username;
    public $email;
    public $password;
    public $id;
    private $dbh;
    private $token;
  
    public function __construct($input)
    {
        $this->username = $input['username'];
        $this->email = $input['mail'];
        $this->password = hash('sha256', $input['password']);
        $this->id = $input['id'];
    }

    public function login()
    {
        $this->dbh = (new DBConnection())->con;
        $sth = $this->dbh->prepare("SELECT id, mail, username, notifications FROM users WHERE active = true AND username = :username AND password = :password");
        $sth->execute(array('username' => $this->username, 'password' => $this->password));
        if ($sth != false)
        {    
            $user = $sth->fetch(PDO::FETCH_ASSOC);
            if ($user)
            {
                return  json_encode($user);
            }
            else
            {
               return null;
            }
        }
        $this->dbh->closeCursor();
        return null;
    }

    public function checkIfExists($field, $value)
    {
        if (!$this->dbh)
            $this->dbh = (new DBConnection())->con;
        $sql = "SELECT users.id, users.username FROM users LEFT JOIN confirmation_tokens ON confirmation_tokens.user_id = users.id WHERE ".$field." = :val AND (users.active = true OR confirmation_tokens.expiration_date > NOW())";
        $sth = $this->dbh->prepare($sql); 
        $sth->execute(array('val' => $value));
        if ($sth != false)
        {    
            $user = $sth->fetch();
            if ($user)
                return true;
            return false;
        }
    }

    public function checkExistingUsers()
    {
        if ($this->checkIfExists("mail", $this->email))
            return 1;
        if ($this->checkIfExists("username", $this->username))
            return 2;
        return 0;
    }

    public function updateUserField($field, $value)
    {
        if (!$this->dbh)
            $this->dbh = (new DBConnection())->con;
        $this->dbh->beginTransaction();  
        $sql = "UPDATE users SET ".$field." = :val"." WHERE id = :id";
        $sth = $this->dbh->prepare($sql); 
        $sth->execute(array('val' => $value, 'id' => $_SESSION['user_id']));
        $this->dbh->commit();
    }

    public function updatePassword($value)
    {
        if (!$this->dbh)
            $this->dbh = (new DBConnection())->con;
        if (!$this->login())
            return 'wrong password';
        $newPassword = hash('sha256', $value);
        $this->dbh->beginTransaction();  
        $sth = $this->dbh->prepare("UPDATE users SET password = :val WHERE id = :id"); 
        $sth->execute(array('val' => $newPassword, 'id' => $this->id));
        $this->dbh->commit();
        return 'success';
    }

    public function resetPassword($value, $token)
    {
        if (!$this->dbh)
            $this->dbh = (new DBConnection())->con;
        $sql = "SELECT users.id FROM users LEFT JOIN reset_tokens ON reset_tokens.username = users.username WHERE reset_tokens.token = ? AND expiration_date > NOW()";
        $sth = $this->dbh->prepare($sql);
        $this->dbh->beginTransaction();
        if ($sth->execute(array($token)))
            $this->id = $sth->fetch();
        if (!$this->id)
            return 'unknown token';
        $newPassword = hash('sha256', $value);
        $sth = $this->dbh->prepare("UPDATE users SET password = :val WHERE id = :id"); 
        $sth->execute(array('val' => $newPassword, 'id' => $this->id[0]));
        $this->dbh->commit();
        return 'success';
    }

    public function sendToDb() 
    {
        try 
        {
            $stmt = $this->dbh->prepare("INSERT INTO users(username, mail, password) VALUES(:username, :mail, :password)");
            $this->dbh->beginTransaction();
            $stmt->execute(array('username' => $this->username, 'mail' => $this->email, 'password' => $this->password));
            $this->id = $this->dbh->lastInsertId();
            $this->dbh->commit();
            $this->token = new TokenConfirmation($this->id);
            $this->token->create();
            if (!$this->token->creationDate)
                throw new Exception("Error - Token was not created.");
            $this->token->register($this->dbh);
        }
        catch (PDOException $e)
        {
            throw new Exception($e);
        }
    }

    public function preregister()
    {
        try
        {
            $this->dbh = (new DBConnection())->con;
            $usedParam = $this-> checkExistingUsers();
            if ($usedParam == 1)
                return 'mail';
            if ($usedParam == 2)
                return 'username';
            $this->sendToDb();
            $this->sendMail();
            return true;
        }
        catch(Exception $e)
        {
            $this->deleteUser();
            return $e->getMessage();
        }
    }

    public function deleteUser()
    {    
        try
        {
            if (!$this->dbh)
                $this->dbh = (new DBConnection())->con;
            $stmt = $this->dbh->prepare("DELETE FROM users WHERE users.id = :id");
            $this->dbh->beginTransaction();
            $stmt->execute(array('id' => $this->id));
            $this->dbh->commit();
        }
        catch (PDOException $e)
        {
            //log
        }
    }

    public function sendMail()
    {
        $content = "Click on the link to pimp your pics "."http://127.0.0.1:8080?page=confirm&token=".$this->token->token;
        mail($this->email, "Welcome to Camagru" , $content, "From: camagru@camagru.com");
    }
}
