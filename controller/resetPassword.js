import { postFunction, checkPassword } from "./camagru.js";

function resetRes(val, arg)
{
  try
  {
    var data = JSON.parse(val);
    if (data.error)
      alert('Error ' + data.error);
    else
  {
        alert('Your password was successefully updated');
        window.location.replace("/");
  }
  }
  catch
  {
    alert('An error occurred, please try later.');
  }
}

function resetValidation()
{
  document.getElementById('passwordChangeValidate').addEventListener('click', function()
  {
    var newPass = document.getElementById('newPassword').value;
        if (!(checkPassword(newPass)))
        {
          alert('Your password must contain at least 1 lowercase alphabetical character, 1 uppercase alphabetical character, 1 numeric character, 1 special character and be from 8 to 10 characters');
          return;
        }
        var data = JSON.stringify({
            "action" : "updatePassword",
            "newPassword" : newPass,
            "reset" : true
           });
        postFunction(data, resetRes);
    })
}

document.addEventListener("DOMContentLoaded", function(event) {
  resetValidation();
});