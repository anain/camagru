<?php

require_once __DIR__."/database.php";

$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

$drop = "DROP TABLE IF EXISTS users, confirmation_tokens, reset_tokens, pictures, comments";
$createUsers = "CREATE TABLE users(id SERIAL NOT NULL, username VARCHAR(60), mail VARCHAR(120), password VARCHAR(70), notifications BOOLEAN DEFAULT true, active BOOLEAN DEFAULT false)";
$createConfirmationTokens = "CREATE TABLE confirmation_tokens(token VARCHAR(60), user_id INT, creation_date DATETIME, expiration_date DATETIME)";
$createResetTokens = "CREATE TABLE reset_tokens(token VARCHAR(60), username VARCHAR(60), creation_date DATETIME, expiration_date DATETIME)";
$createComments = 'CREATE TABLE comments(id SERIAL NOT NULL, user_id INT, content VARCHAR(200), img_id INT)';
$createPictures = 'CREATE TABLE pictures(id SERIAL NOT NULL, user_id INT, comments int DEFAULT 0, love INT DEFAULT 0, date DATETIME)';
$InitUsers = "INSERT INTO users(username, mail, password) VALUES('admin', 'admin@yopmail.com', '4fc26a008917ed0826c0015ca2836d3a29a86b6ed133558d3a9ecbab9fe31a05')";
$array = array();
array_push($array, $drop, $createResetTokens, $createUsers, $createConfirmationTokens, $createComments, $createPictures, $InitUsers);
foreach ($array as $cmd)
{
    echo $cmd;
    $dbh->beginTransaction();
    $dbh->exec($cmd);
    $dbh->commit();
}

$stm = $dbh->query("SELECT id FROM users WHERE username = 'admin'");
$id = $stm->fetch()[0];
if ($handle = opendir(__DIR__.'/../static/gallery')) 
{
    while (false !== ($entry = readdir($handle))) 
    {
        if(is_array(@getimagesize(__DIR__.'/../static/gallery/'.$entry)))
        {
            $entry = explode('.', $entry);  
            $stmt = $dbh->prepare("INSERT INTO pictures(id, user_id, date) VALUES(:id, :user_id, NOW())");
            $dbh->beginTransaction();
            $stmt->execute(array('id' => $entry[0], 'user_id'=> $id));
            $dbh->commit();
        }
    }
}