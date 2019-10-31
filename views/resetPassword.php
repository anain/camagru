<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Camagru</title>
        <link href="views/style/camagru.css" rel="stylesheet"> 
        <link href="views/style/parameters.css" rel="stylesheet"> 
        <link rel="shortcut icon" href="static/images/favicon.ico">
    </head>
    <body>   
    <?php require_once("elements/header.php");?>
    <div id="box">
    <h1>password reset</h1>
    <div id="reset"
        ><div>please enter a new password:</div>
        <div>
            <input type="password" maxlength="60" id="newPassword" name="newPassword"/> 
            <button id="passwordChangeValidate" class="change">validate</button>   
        </div> 
    </div>
    <?php require_once("elements/footer.php");?>
    </div>
    </div>
    <script type="module"  src="../controller/resetPassword.js"></script>
    </body>
</html>