<?php

require_once __DIR__."/../model/User.php";
require_once __DIR__."/../model/Token.php";
require_once __DIR__."/../model/Gallery.php";
require_once __DIR__."/../model/PictureCreation.php";
require_once __DIR__."/../model/Comment.php";
require_once __DIR__."/../model/PictureDescription.php";

session_start();

function showHome()
{
    require_once('./views/index.php');
}

function loadGallery() 
{
    try
    {
        $gallery = new Gallery();
        $gallery->init();
        echo JSON_encode($gallery->pics);
    }
    catch(Exception $e)
    {
        echo JSON_encode(array('error' => $e->getMessage()));
    }

}

function loadMoreGallery($load) 
{
    try
    {
        $gallery = new Gallery();
        $gallery->load(28 * $load);
        echo JSON_encode($gallery->pics);
    }
    catch(Exception $e)
    {
        echo JSON_encode(array('error' => $e->getMessage()));
    }
  
}

function loadSidePics($offset)
{
    try
    {
        $gallery = new Gallery();
        $gallery->select($_SESSION['user_id'], $offset);
        echo JSON_encode($gallery->pics);
    }
    catch(Exception $e)
    {
        echo JSON_encode(array('error' => $e->getMessage()));
    }
}