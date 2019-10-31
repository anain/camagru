<?php
session_start();
?>
<header>
    <img onclick='window.location.href="/"' id="home" src="../../static/images/logo.png">
    <?php 
    if (isset($_SESSION['user_id'])) 
    { ?>
        <div class="navButton" onclick='window.location.href="/?page=parameters"' id="account">account</div>
        <div class="navButton" onclick='window.location.href="/?action=logout"' id="out">leave</div>
        <div class="navButton" onclick='window.location.href="/?page=create"' id="goCreate">create</div>
       
    <?php 
    }
    else
    { ?>
        <div class="navButton" id="signIn">sign in</div>
        <div class="navButton" id="signUp">sign up</div>
    <?php 
    } ?>
</header>