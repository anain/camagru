<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Camagru</title>
        <link href="views/style/camagru.css" rel="stylesheet"> 
        <link href="views/style/create.css" rel="stylesheet"> 
        <link rel="shortcut icon" href="static/images/favicon.ico">
    </head>
    <body>   
    <?php require_once("elements/header.php");?>
    <div id="content"> 
        <div id="main" class="column main">
            <div id="createButtonsPanel">
                <div class="createButton" id="upload">upload a pic</div>
                <input type="file" id="uploadButton" accept="image/png, image/jpeg" value="upload a pic">
                <div class="createButton" id="reset">reset</div>
                <div class="createButton" id="webcam">webcam on</div>
                <div class="createButton" id="save">cheese !</div>
            </div>
            <div id="videoContainer">
                <img id="camPic" class="videoElem" src="static/images/webcam.jpg">
                <video autoplay="true" id="videoElement" class="videoElem"> </video>
                <canvas id="canvasFront" class="videoElem"></canvas>  
                <canvas id="canvasBack" class="videoElem"></canvas>  
            </div>
            <!-- <div id="components-list-wrapper"> -->
                <div id="components-list">
                    <?php
                    if ($handle = opendir('static/components')) 
                    {
                        while (false !== ($entry = readdir($handle))) 
                        {
                            if(is_array(@getimagesize('static/components/'.$entry)))
                                echo '<div class="component"> <img src="static/components/'.$entry.'" id="'.$entry.'"></div>';
                        }   
                    }
                    ?>
                </div> 
            <!-- </div> -->
        </div>
        <div id="side" class="column side">
        </div>
    </div>
    <?php require_once("elements/footer.php");?>
    <script type="module"  src="../controller/createSideBar.js"></script>
    <script type="module"  src="../controller/create.js"></script>
    <script type="module"  src="../controller/camagru.js"></script>
    </body>
</html>