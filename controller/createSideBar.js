import { Box, getFunction, createPictureDiv, closeFocusHandler } from "./camagru.js";

var camOn = false;
var canvasVideo;
var canvasElem;
var contextElem;
var contextVideo;
var video;
var fullPic = 0;
var imgId;
var renderableHeight;
var renderableWidth;
var xStart = 0;
var yStart = 0;

function sidePics(val) 
{
  try
  {
    var arrayPics = JSON.parse(val);
    var i = -1;
    while (++i < arrayPics.length && arrayPics[i])
    {
      var box = new Box(arrayPics[i]['id'], arrayPics[i]['love'], arrayPics[i]['comments']);
      var div = createPictureDiv(box, 0, document.body);
      document.getElementById('side').appendChild(div);
    }
    localStorage.setItem('load', arrayPics.length);
  }
  catch
  {
    alert('Error loading.');
  }
}

function sideLoadOnsScroll()
{
    document.getElementById('side').addEventListener('scroll', function()
    {
      if (this.scrollTop >= this.scrollHeight / 3)
        getFunction('/?action=loadsidepics&offset=' + localStorage.getItem('load'), sidePics);
    })
}


document.addEventListener("DOMContentLoaded", function() {
  getFunction('/?action=loadsidepics&offset=0', sidePics);
  localStorage.setItem('load', 0);
  sideLoadOnsScroll();
  closeFocusHandler(document.getElementsByClassName('column'));
});