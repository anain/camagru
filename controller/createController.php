<?php

require_once __DIR__."/../model/User.php";
require_once __DIR__."/../model/Token.php";
require_once __DIR__."/../model/Gallery.php";
require_once __DIR__."/../model/PictureCreation.php";
require_once __DIR__."/../model/Comment.php";
require_once __DIR__."/../model/PictureDescription.php";

session_start();

function create()
{
    if (!empty($_SESSION['user_id'])) 
        require_once('./views/create.php');
    else
    {
        require_once('./views/index.php');
    }
}

function savePic($data)
{
    try
    {
        $pic = new PictureCreation($_SESSION['user_id']);
        $pic->merge($data);
        echo json_encode(array('success' => true, 'id' => $pic->id));
    }
    catch(Exception $e)
    {
        echo json_encode(array('error' => $e->getMessage()));
    }
}