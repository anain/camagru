<?php $title = 'Camagru'; 

require_once('controller/accountController.php');
require_once('controller/galleryController.php');
require_once('controller/createController.php');
require_once('controller/miscController.php');

session_start();

$page = '';

function sendError($e)
{
    echo json_encode(array('error' => "An error occured - Please try later."));
}

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
   if (isset($_GET['page']))
        $page = trim(htmlspecialchars($_GET['page']));
    if ($page == 'index' || !sizeof($_GET))
        showHome();
    else if ($page == 'create')
        create();
    else if ($page == 'parameters')
        showParameters();
    else if ($page == 'confirm' && isset($_GET['token']))
        confirmRegistration($_GET['token']);
    else if ($page == 'reset' && isset($_GET['token']))
        allowResetPassword($_GET['token']);
    else if (isset($_GET['action']) && $_GET['action'] == 'logout')
        logout();
    else if (isset($_GET['action']) && $_GET['action'] == 'getgallery')
        loadGallery();
    else if (isset($_GET['action']) && $_GET['action'] == 'loadMoreGallery')
        loadMoreGallery($_GET['load']);
    else if (isset($_GET['action']) && $_GET['action'] == 'loadsidepics' && isset($_GET['offset']))
        loadSidePics($_GET['offset']);
    else
        show404();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['action'])
    $_POST = json_decode(file_get_contents('php://input'), 1);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'signup')
{
    try
    {
        register($_POST['username'], $_POST['mail'], $_POST['password']);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'signin')
{
    try
    {
        login($_POST['username'], $_POST['password']);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'save')
{
    try
    {
        savePic($_POST);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'resetPass')
{
    try
    {
        resetPassword($_POST['username']);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'updateUser')
{
    try
    {
        updateUserField($_POST['field'], $_POST['value']);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'updatePassword')
{
    try
    {
        updatePassword($_POST);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'sendComment')
{
    try
    {
        sendComment($_POST);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'sendLike')
{
    try
    {
        sendLike($_POST['target']);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'updateNotif')
{
    try
    {
        updateNotif($_POST['check']);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'deletePic')
{
    try
    {
        deletePic($_POST['target']);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'getComments' && isset($_POST['target']))
{
    try
    {
        getComments($_POST['target']);
    }
    catch (Exception $e)
    {
        sendError($e);
    }
}