import { containSpecialChar, postFunction, checkPassword } from "./camagru.js";

function updatePageFields(val)
{
  try
  {
    var data = JSON.parse(val);
    if (data.error)
      alert('Sorry, an error occurred.');
    else
    {
      alert('Your information was successfully updated!');
      window.location.replace("/?page=parameters");
    }
  }
  catch
  {
    alert('Oups, an error occurred.');
  }
}

function showForms()
{
  var changeButtons = document.getElementsByClassName('change static');
  for (var i = 0; i < changeButtons.length; i++)
  {
    changeButtons[i].addEventListener('click', function() 
    {
      var id = this.id;
      var elem = document.getElementById(id + 'Form');
      var currentDisplay = (getComputedStyle(elem)).display;
      if (currentDisplay === "none")
        elem.style.display = 'table-row';
      else
        elem.style.display = 'none';
    })
  }

  document.getElementById('usernameChangeValidate').addEventListener('click', function()
  {
    if (containSpecialChar(document.getElementById('newUsername').value))
    {
      alert("Your username contains forbidden characters.");
      return;
    }
    var data = JSON.stringify({
      "action" : "updateUser",
      "field" : "username",
      "value" : document.getElementById('newUsername').value
    });
    if (document.getElementById('newUsername').value)
      postFunction(data, updatePageFields)
  })

  document.getElementById('mailChangeValidate').addEventListener('click', function ()
  {
    var data = JSON.stringify({
      "action" : "updateUser",
      "field" : "mail",
      "value" : document.getElementById('newMail').value
    });
    if (document.getElementById('newMail').value.trim())
      postFunction(data, updatePageFields)
  })

  document.getElementById('passwordChangeValidate').addEventListener('click', function ()
  {
    var oldPass = document.getElementById('oldPassword').value;
    var newPass = document.getElementById('newPassword').value;
    if (!oldPass)
    {
        alert('You have to enter your current password to be able to change it.');
        return;
    }
    if (!(checkPassword(newPass)))
    {
      alert('Your password must contain at least 1 lowercase alphabetical character, 1 uppercase alphabetical character, 1 numeric character, 1 special character and be from 8 to 10 characters');
      return;
    }
    var data = JSON.stringify({
      "action" : "updatePassword",
      "oldPassword" : oldPass,
      "newPassword" : newPass
     });
    postFunction(data, updatePageFields)
  })
}

function sendTickChangeRes(val)
{
  try
  {
    var data = JSON.parse(val);  
    if (data.error)
          alert("Sorry, an error occurred.");
    else
      alert("Your preferences were updated");
    }
    catch
    {
      alert("Error.");
    }
}

function sendTickChange(value) 
{
    var data = JSON.stringify({
      "action": "updateNotif",
      "check": value
    })
    postFunction(data, sendTickChangeRes);
}

function tickBox()
{
  document.getElementById('checkBoxStatement').addEventListener('click', function() 
  {
    var checkBox = document.getElementById('checkBox');
    checkBox.checked = !checkBox.checked;
    sendTickChange(checkBox.checked);
  })
}

document.addEventListener("DOMContentLoaded", function() {
  showForms();
  tickBox();
  document.getElementById('checkBox').addEventListener('change', function() {
    sendTickChange(this.checked);
  })
})