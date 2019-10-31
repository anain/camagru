<?php

require_once __DIR__."/../model/User.php";
require_once __DIR__."/../model/Token.php";
require_once __DIR__."/../model/Gallery.php";
require_once __DIR__."/../model/PictureCreation.php";
require_once __DIR__."/../model/Comment.php";
require_once __DIR__."/../model/PictureDescription.php";

session_start();

function register($username, $mail, $password)
{
    $user = new User(['username' => $username, 'mail' => htmlspecialchars($mail), 'password' => $password]);
    try
    {
        $ret = $user->preregister();
        echo JSON_encode(array('success' => $ret));
    }
    catch(Exception $e)
    {
        echo JSON_encode(array('error' => $e->getMessage()));
    }
}

function confirmRegistration($token)
{
    $msg = (new TokenConfirmation())->validateAccount($token);
    if ($msg == 'success')
        echo '<script type="text/javascript">alert("Your account was successfully validated !");</script>';
    else
        echo '<script type="text/javascript">alert("'.$msg.'");</script>'; 
    require_once('./views/index.php');
}

function showParameters()
{
    if (!empty($_SESSION['user_id'])) 
    {
        require_once('./views/parameters.php');
    }
    else
    {
        require_once('./views/index.php');
    }
}

function login($username, $password)
{
    $user = new User(array('username' => $username, 'password' => $password));
    $userData = json_decode($user->login(), true);
    if ($userData)
    {
        $_SESSION['user_id'] = $userData["id"];;
        $_SESSION['mail'] = $userData["mail"];
        $_SESSION['username'] = $userData["username"];
        if ($userData["notifications"] == 1)
            $_SESSION["notifications"] = "true";
        else
            $_SESSION["notifications"] = "false";
        echo JSON_encode(array('success' => true));
    }
    else
        echo JSON_encode(array('error' => 'notFound'));
}

function logout()
{
    unset($_SESSION);
    session_destroy();
    showHome();
}

function resetPassword($username)
{
    $u = new User(array('username' => $username));
    if (!$u->checkIfExists("username", $u->username))
        echo JSON_encode(array('error' => 'unknown'));
    else
    {
        try
        {
            $token = (new TokenReset($u));
            $token->create();
            $token->register();
            $token->sendMail();
            echo JSON_encode(array('success' => true));
        }
        catch(Exception $e)
        {
            echo JSON_encode(array('error' => $e->getMessage()));
        }
    }
}

function allowResetPassword($token)
{
    $_SESSION['token'] = $token;
    require_once('./views/resetPassword.php');
}

function updateUserField($field, $value)
{
    try
    {
        $u = new User(array('id' => $_SESSION['user_id']));
        $u->updateUserField($field, $value);
        if ($_SESSION[$field])
            $_SESSION[$field] = $value;
        echo json_encode(array('success' => true));
    }
    catch(PDOException $exception)
    { 
        echo json_encode(array('error1' => $exception->getMessage())); 
    } 
    catch(Exception $e)
    {
        echo json_encode(array('error2' => $e->getMessage())); 
    }
}

function updateNotif($value)
{
    try
    {
        $u = new User(array('id' => $_SESSION['user_id']));
        $u->updateUserField("notifications", $value);
        if ($value == 0)
            $_SESSION['notifications'] = "false";
        else
            $_SESSION['notifications'] = "true";
        echo json_encode(array('success' => true));
    }
    catch(PDOException $exception)
    { 
        echo json_encode(array('error1' => $exception->getMessage())); 
    } 
    catch(Exception $e)
    {
        echo json_encode(array('error2' => $e->getMessage())); 
    }
}

function updatePassword($input)
{
    try 
    {
        if ($input['oldPassword'])
        {
            $u = new User(array('username' => $_SESSION['username'], 'mail' => $_SESSION['mail'], 'id' => $_SESSION['user_id'], 'password' => $input['oldPassword']));
            $res = $u->updatePassword($input['newPassword']);
        }
        else if ($input['reset'] === true)
            $res = (new User($input))->resetPassword($input['newPassword'], $_SESSION['token']);
        unset($_SESSION['token']);
        if ($res === 'success')
            echo json_encode(array('success' => true));
        else
            echo json_encode(array('error' => $res));
    }
    catch(Exception $e)
    {
        echo json_encode(array('error' => 'error: ' +  $e->getMessage()));
    }
}