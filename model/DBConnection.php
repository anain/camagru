<?php

Class DBConnection 
{   
    public $con;
    
    public function __construct()
    {
        $this->con = new PDO('mysql:host=localhost:3307;dbname=camagru;charset=utf8','root','rootroot'); 
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->con->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
    }    
}