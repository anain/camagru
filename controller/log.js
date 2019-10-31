import { containSpecialChar, postFunction, getFunction, checkPassword } from "./camagru.js";

function signupMove()
{
  document.getElementById('signUp').addEventListener('click', function() 
  {
    if (document.getElementById('signUpForm').style.visibility == "visible")
    {
      document.getElementById('signUpForm').style.visibility = "hidden";
      document.getElementById('gallery').style.opacity = 1;
    }
    else
    {
      document.getElementById('signInForm').style.visibility = "hidden";
      document.getElementById('signUpForm').style.visibility = "visible";
      document.getElementById('gallery').style.opacity = 0.33;
    }
  })
}

function signInMove()
{
  document.getElementById('signIn').addEventListener('click', function()
  {
    if (document.getElementById('signInForm').style.visibility == "visible")
    {
      document.getElementById('signInForm').style.visibility = "hidden";
      document.getElementById('gallery').style.opacity = 1;
    }
    else
    {
      document.getElementById('signUpForm').style.visibility = "hidden";
      document.getElementById('signInForm').style.visibility = "visible";
      document.getElementById('gallery').style.opacity = 0.33;
    }
  })
}

function galleryMove() 
{
  document.getElementById('gallery').addEventListener('click', function()
  {
    document.getElementById('signInForm').style.visibility = "hidden";
    document.getElementById('signUpForm').style.visibility = "hidden";
    document.getElementById('gallery').style.opacity = 1;
  })
}

function signUpRet(val)
{
  try
  {
    var data = JSON.parse(val);
    if (data.error)
        alert("Sorry, an error occurred.");
    else if (data.success == 'mail')
        alert("This mail address is already registered");
    else if (data.success == 'username')
        alert("This username is already registered");
    else if (data.success == true)
        alert("Check your mails to validate your account.");
    else
      alert("Oups, an error occurred.");
  }
  catch
  { 
    alert("Oups, an error occurred.");
  }
}

function signUpSend()
{
  document.getElementById('signUpButton').addEventListener('click', function (e) {
    e.preventDefault();
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var mail = document.getElementById('mail').value;
    if (containSpecialChar(username))
    {
      alert("Your username contains forbidden characters.");
      return;
    }
    if (!username.trim())
    {
      alert("You must enter a username.");
      return ;
    }
    if (!mail.trim())
    {
      alert("You must enter a mail address.");
      return;
    }
    if (!password)
    {
      alert("You must enter a password. Your password must contain at least 1 lowercase alphabetical character, 1 uppercase alphabetical character, \
      1 numeric character, 1 special character and be from 8 to 10 characters");
      return ;
    }
    if (!(checkPassword(password)))
    {
      alert('Your password must contain at least 1 lowercase alphabetical character, 1 uppercase alphabetical character, 1 numeric character, 1 special character and be from 8 to 10 characters');
      return;
    }
    var data = JSON.stringify({
     "action" : "signup",
     "username" : username,
     "mail" : mail,
     "password" : password
    });
    postFunction(data, signUpRet);
  })
} 

function signInRes(val)
{
  try
  {
    var data = JSON.parse(val);
    if (data.error === 'notFound')
      alert('Wrong credentials.');
    else if (data.success == true)
      window.location.replace("/");
    else   
      alert('Error');
  }
  catch
  {
    alert('Error');
  }
}

function signInSend()
{
  document.getElementById('signInButton').addEventListener('click',function (e) 
  {
    e.preventDefault();
    var data = JSON.stringify({
     "action" : "signin",
     "username" : document.getElementById('usernameLog').value,
     "password" : document.getElementById('passwordLog').value
    });
    postFunction(data, signInRes);
  })
}
  
function resetPassRes(val)
{
  try
  {
    var data = JSON.parse(val);
    if (data.success == true)
      alert('We sent you a mail to reset your password.');
    else if (data.error === 'unknown')
      alert('This username is not registered.');
    else
      alert("An error occurred.");
  }
  catch
  {
    alert('Error');
  }
}

function resetPassword()
{
  document.getElementById('forgottenPass').addEventListener('click', function(e)
  {
    e.preventDefault();
    var data = JSON.stringify({
      "action" : "resetPass",
      "username" : document.getElementById('usernameLog').value,
     });
    postFunction(data, resetPassRes)
  })
}

document.addEventListener("DOMContentLoaded", function() {
  if (document.getElementById('signUp'))
    signupMove();
  if (document.getElementById('signIn'))
    signInMove();
  galleryMove();
  signUpSend();
  signInSend();
  resetPassword();
  });