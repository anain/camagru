<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Camagru</title>
        <link href="views/style/camagru.css" rel="stylesheet"> 
        <link href="views/style/index.css" rel="stylesheet"> 
        <link rel="shortcut icon" href="static/images/favicon.ico">
    </head>
    <body>   
    <?php require_once("elements/header.php");?>
    <?php require_once("elements/log.php");?>
    <?php require_once("elements/registration.php");?>
    <div class="row" id="gallery">
    </div>        
    <script type="module" src="../controller/log.js"></script>
    <script type="module" src="../controller/indexLoad.js"></script>
    </body>
</html>