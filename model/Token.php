<?php

require_once __DIR__."/DBConnection.php";

date_default_timezone_set('Europe/Paris');

Class Token 
{   
    public $token;
    public $expirationDate;
    public $creationDate;

    public function create()
    {
        $bytes = openssl_random_pseudo_bytes(12, $cstrong);
        $this->token = bin2hex($bytes);
        $now = new Datetime('now');
        $this->creationDate =  $now->format('Y-m-d H:i:s');
        $expiration = $now->add(new DateInterval('P1D'));
        $this->expirationDate = $expiration->format('Y-m-d H:i:s');
    }
}