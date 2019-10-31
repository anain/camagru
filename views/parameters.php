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
            <h1>account</h1>
            <table id="table">
                <tr>
                    <td>
                        username
                    </td>
                    <td>
                        <?php 
                            echo $_SESSION['username'];
                        ?>
                    </td>
                    <td>
                        <span class="change static" id="usernameChange">change</span>
                    </td>
                </tr>
                <tr class="changeForm" id="usernameChangeForm">
                    <td colspan=2>
                        <span>new username</span>
                        <input type="text" id="newUsername" name="newUsername"/> 
                    </td>
                    <td>
                        <button id="usernameChangeValidate" maxlength="60" class="change">validate</button>    
                    </td>    
                </tr>
                <tr>
                    <td>    
                        mail
                    </td>
                    <td>
                        <?php 
                            echo $_SESSION['mail'];
                        ?>
                    </td>
                    <td>
                        <span class="change static" id="mailChange">change</span>
                    </td>
                </tr>
                <tr class="changeForm" id="mailChangeForm">
                    <td colspan=2>
                    <span>new mail adress</span>
                    <input type="text" id="newMail" maxlength="60" name="newMail"/> 
                    </td>
                    <td>
                        <button id="mailChangeValidate" class="change">validate</button>    
                    </td>    
                </tr>
                 <tr>
                    <td>    
                        password
                    </td>
                    <td>
                    </td>
                    <td>
                        <span class="change static" id="passwordChange">change</span>
                    </td>
                </tr>
                <tr class="changeForm" id="passwordChangeForm">
                    <td colspan=2>
                        <span>current password</span>
                        <input type="password" id="oldPassword" name="oldPassword" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"/> 
                        <br/>
                        <span>new password</span>
                        <input type="password" id="newPassword" maxlength="60" name="newPassword"/> 
                    </td>
                    <td>
                        <button id="passwordChangeValidate" class="change">validate</button>    
                    </td>    
                </tr>
                <tr>
                    <td colspan=3>
                    <input type="checkbox" checked="<?php echo $_SESSION["notifications"]; ?>" id="checkBox"><span id="checkBoxStatement" class="change">I want to receive notifications when a creation of mines is commented</span> 
                    </td>
                </tr>
            </table>
        </div>
    <?php require_once("elements/footer.php");?>
    </div>
    <script type="module"  src="../controller/parameters.js"></script>
    </body>
</html>