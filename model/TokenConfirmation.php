<?php

require_once __DIR__."/DBConnection.php";
require_once __DIR__."/Token.php";

Class TokenConfirmation extends Token
{
    public function __construct($id)
    {
        $this->userId = $id;
    }

    public function register()
    {
        try 
        {
            $this->dbh = (new DBConnection())->con;
            $stmt = $this->dbh->prepare("INSERT INTO confirmation_tokens(token, user_id, creation_date, expiration_date) VALUES(:token, :user_id, :creation_date, :expiration_date)");
            $this->dbh->beginTransaction();
            $stmt->execute(array('token' => $this->token, 'user_id' => $this->userId, 'creation_date' => $this->creationDate, 'expiration_date' => $this->expirationDate)); 
            $this->dbh->commit();
        }
        catch (PDOException $e)
        {
            $this->dbh->rollback();
            throw new Exception("Error - Token was not created.\n");
        }
    }

    public function validateAccount($token)
    {
        $this->token = $token;
        try
        {
            $this->dbh = (new DBConnection())->con;    
            $this->dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $sth = $this->dbh->prepare("SELECT * FROM confirmation_tokens WHERE token = :token AND expiration_date > NOW()");
            $sth->execute(array('token' => $this->token));
            if ($sth != false && ($token = $sth->fetch()))
            {    
                $this->userId = $token['user_id'];
                $this->dbh->beginTransaction();  
                $stmt = $this->dbh->prepare("UPDATE users SET active = true WHERE users.id = :id");
                $stmt->execute(array('id' => $this->userId));
                $this->dbh->commit();
                $this->dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
                return 'success';
            }
            else
                return 'User not found';
        }
        catch (Exception $e)
        {
            return 'Error : '.$e->getMessage();
        }
    }
}