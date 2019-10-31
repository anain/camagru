<?php

require_once __DIR__."/../model/User.php";
require_once __DIR__."/../model/Token.php";
require_once __DIR__."/../model/Gallery.php";
require_once __DIR__."/../model/PictureCreation.php";
require_once __DIR__."/../model/Comment.php";
require_once __DIR__."/../model/PictureDescription.php";

session_start();

function show404()
{
    require_once('./views/404.php');
}

function sendComment($input)
{
    if (!$_SESSION['username'])
    {
        echo json_encode(array('error' => 'logError'));
        return;
    }
    if (!($input['content'] && is_numeric($input['target'])))
    {
        echo json_encode(array('error' => 'error'));
        return;
    }
    try 
    {
        (new Comment($_SESSION['user_id'], $_SESSION['username'], htmlspecialchars($input['content']), $input['target']))->sendToDb();
        echo json_encode(array('username' => $_SESSION['username'], 'target' => $input['target']));
    }
    catch(Exception $e)
    {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function getComments($picId)
{
    try
    {
        $p = new PictureDescription($picId);
        $p->getComments();
        echo json_encode($p->commentsList);
    }
    catch(Exception $e)
    {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function sendLike($target)
{
    if (!($_SESSION['user_id']))
    {
        echo json_encode(array('error' => 'logError'));
        return;
    }
    if (!(is_numeric($target)))
    {
        echo json_encode(array('error' => 'error1'));
        return;
    }
    try 
    {
        (new PictureDescription($target))->updateLoveNumber();  
        echo json_encode(array('success' => 1));
    }
    catch(Exception $e)
    {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function deletePic($target)
{
    if (!($_SESSION['user_id']))
    {
        echo json_encode(array('error' => 'logError'));
        return;
    }    
    try 
    {
        (new PictureDescription($target))->delete();  
        echo json_encode(array('success' => true));
    }
    catch(Exception $e)
    {
        echo json_encode(array('error' => $e->getMessage()));
    }

}