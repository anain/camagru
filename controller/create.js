import { Box, postFunction, createPictureDiv } from "./camagru.js";

var canvasVideo;
var canvasElem;
var contextElem;
var contextVideo;
var video;
var imgId;
var renderableHeight;
var renderableWidth;
var xStart = 0;
var yStart = 0;
var basePic = 0;
var camOn = false;

function startCamera() 
{
  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) 
  {
    document.getElementById('uploadButton').style.display = 'none';
    document.getElementById('upload').style.color = 'grey';
    navigator.mediaDevices.getUserMedia({ video: true }).then(stream => video.srcObject = stream)
    .then(function() 
    {
      document.getElementById('camPic').style.display = "none";
      var playPromise = video.play();
      if (playPromise !== undefined) 
      {
        playPromise.then(function() 
        { 
          camOn = true;   
          document.getElementById('components-list').style.cursor = "pointer";
        }).catch(error => function(){});
      }
    })
    .catch(function (err) {
      alert("You need to allow access to your webcam to take a picture.");
    });
  }
};

function uploadButton()
{
  document.getElementById('upload').addEventListener('click', function()
  {
    document.getElementById('uploadButton').style.display = 'block';
    document.getElementById('upload').style.color = 'purple';
  })
}

function test()
{
  camOn = false;
}

function setImgtoCanvas()
{
  var camPic = document.getElementById('camPic');
  contextVideo.clearRect(0, 0, canvasVideo.width, canvasVideo.height);
  camPic.style.display = "none";
  canvasVideo.width = camPic.width;
  canvasVideo.height = camPic.height;
  canvasElem.width = camPic.width;
  canvasElem.height = camPic.height;
  resize(this);
  contextVideo.drawImage(this, 0, 0,  renderableWidth, renderableHeight);
  camOn = false;
  if (video.srcObject)
    video.srcObject.getTracks()[0].stop();
  video.style.display = "none";
  document.getElementById('components-list').style.cursor = "pointer";
}

function fail()
{
  alert("The file could not be loaded.");
}

function setUploadedPic() 
{
  document.getElementById('uploadButton').addEventListener('click', function()
  {
    document.getElementById('uploadButton').value = '';
  })
  document.getElementById('uploadButton').addEventListener('change', function()
  {
    resetElem();
    var img = new Image();
    img.onload = setImgtoCanvas;
    img.onerror = fail;
    img.src = URL.createObjectURL(this.files[0]);
    basePic = 1;
  });
  
};

function allowPictureButton()
{
  document.getElementById('webcam').addEventListener('click', function(e) 
  {
    e.stopPropagation();
    if (!camOn)
      startCamera();
  })
}

function camClick() 
{
  document.getElementById('videoContainer').addEventListener('click', function(e) 
  {
    if (!camOn )
      startCamera();
    else 
      savePic();
  })
}

function enterTakePic() 
{
  document.addEventListener('keypress', function (e) {
    if (document.getElementById('focus-img-box') || !imgId)
      return;
    savePic();
    })
};

function savePicRes(val)
{
  try
  {
    var data = JSON.parse(val);
    if (data.error)
      alert('Sorry, the picture could not be saved...');
    else if (data.success === true)
    {
      var box = new Box(data.id, 0, 0);
      var div = createPictureDiv(box, 0, document.body);
      document.getElementById('side').prepend(div);
      localStorage.setItem('load', parseInt(localStorage.getItem('load')) + 1);
    }
    else
      alert('Error');
  }
  catch
  {
    alert('Error');
  }
  finally
  {
    resetElem();
  }
}

function takePicture()
{
  if (!imgId)
  {
    alert('You must select an element.');
    return;
  }
  var audio = new Audio('../static/sounds/clic.mp3');
  contextVideo.drawImage(video, 0, 0,  canvasVideo.width, canvasVideo.height);
  audio.play();
  camOn = false;
  basePic = 1;
}

function canvasEmpty()
{
  var canvasData = contextVideo.getImageData(0, 0, canvasVideo.offsetWidth, canvasVideo.offsetHeight);
  for (var i = 0; i < canvasData.data.length; i += 4)
    if (canvasData.data[i + 3] !== 0) 
      return false;
  return true;
}

function savePic() 
{
  if (camOn && canvasEmpty())
    takePicture();
  if (!basePic)
    return;
  var imgBase64 = canvasVideo.toDataURL();
  var data = JSON.stringify({
    "action": "save",
    "picBase": imgBase64,
    "component": imgId,
    "xStart": xStart,
    "yStart": yStart,
    "renderableHeight": renderableHeight,
    "renderableWidth": renderableWidth
     });
     postFunction(data, savePicRes);
}

function resize(img)
{
  var ratio = img.width / img.height;
  renderableWidth = canvasElem.width;
  renderableHeight = renderableWidth / ratio;
  if (renderableHeight > canvasElem.height)
  {
    renderableHeight = canvasElem.height;
    renderableWidth = renderableHeight * ratio;
  }
}

function selectComponent()
{
  var components = document.getElementsByClassName('component');
  for (var i = 0; i < components.length; i++)
    components[i].addEventListener('click', function() 
    {
      contextElem.clearRect(0, 0, canvasElem.width, canvasElem.height);
      renderableHeight = null;
      renderableWidth= null;
      xStart = 0;
      yStart = 0;
      var img = this.children[0];
      imgId =  this.children[0].id;
      resize(img);
      if (imgId == 'eau.png')
        renderableHeight = canvasElem.height;
      if (imgId.indexOf('small_') == 0)
      {
        xStart = canvasVideo.width / 2;
        yStart = canvasVideo.height / 3;
        renderableHeight = 0.10 * renderableHeight;
        renderableWidth = 0.10 * renderableWidth;
      }
      contextElem.drawImage(document.getElementById(imgId), xStart, yStart, renderableWidth, renderableHeight);
      document.getElementById('save').style.cursor = "pointer";
    })
}

function resetElem()
{
  contextVideo.clearRect(0, 0, canvasVideo.width, canvasVideo.height);
  contextElem.clearRect(0, 0, canvasElem.width, canvasElem.height);
  imgId = null;
  renderableHeight = null;
  renderableWidth= null;
  xStart = 0;
  yStart = 0;
  basePic = 0;
  document.getElementById('components-list').style.cursor = "not-allowed";
  document.getElementById('save').style.cursor = "not-allowed";
  if (video.srcObject)
  {
      startCamera();
      video.style.display = "";
  }
  else 
    camPic.style.display = 'block';
}

document.addEventListener("DOMContentLoaded", function() {
  canvasElem = document.getElementById('canvasFront');
  canvasVideo = document.getElementById('canvasBack');
  contextElem = canvasElem.getContext('2d');
  contextVideo = canvasVideo.getContext('2d');
  video = document.getElementById('videoElement');
  var camPic = document.getElementById('camPic');
  canvasVideo.width = camPic.width;
  canvasVideo.height = camPic.height;
  canvasElem.width = camPic.width;
  canvasElem.height = camPic.height
  document.getElementById('save').addEventListener('click', savePic);
  allowPictureButton();
  camClick();
  enterTakePic();
  selectComponent();
  uploadButton();
  setUploadedPic();
  document.getElementById('reset').addEventListener('click', resetElem);
})