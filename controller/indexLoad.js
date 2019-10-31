import { Box, getFunction, createPictureDiv, closeFocusHandler} from "./camagru.js";

function fillGalleryColumn(arrayPics)
{
    var columns = document.getElementsByClassName('column');
    var i = 0;
    var c = 0;
    while ( i < arrayPics.length)
    {
        var box = new Box(arrayPics[i]['id'], arrayPics[i]['love'], arrayPics[i]['comments']);
        var div = createPictureDiv(box, 1, document.getElementById('gallery'));
        i++;
        if (!div)
            continue;
        columns[c].appendChild(div);
        if (c === 3)
            c = 0;
        else
            c++;
    }
}

function buildGallery(val)
{
   try
   {
        var data = JSON.parse(val);
        var i = -1;
        while (++i < 4)
        {
            var column = document.createElement('div');
            column.className = 'column';
            document.getElementById('gallery').appendChild(column);
        }
        localStorage.setItem('load', 0); 
        fillGalleryColumn(data);     
    }
    catch
    {
        alert("Error.");
    }
}

function addToGallery(val)
{
    try
    {
        var data = JSON.parse(val);
        var i = -1;
        fillGalleryColumn(data);
    }
    catch
    {
        alert("Error.");
    }
}

function loadOnsScroll()
{
    document.addEventListener('scroll', function(e)
    {
        var position = window.pageYOffset; 
        var bottom = document.body.offsetHeight - window.innerHeight;
        if (position >= bottom * 2/3)
        {
            e.stopPropagation();
            localStorage.setItem('load', parseInt(localStorage.getItem('load')) + 1);
            getFunction('/?action=loadMoreGallery&load=' + localStorage.getItem('load'), addToGallery);
        }
    })
}

document.addEventListener("DOMContentLoaded", function(event) {
    getFunction('/?action=getgallery', buildGallery);
    loadOnsScroll();
    closeFocusHandler(document.getElementsByClassName('column'));
  });