export function postFunction(data, func, arg)
{
    try
    {
        var xhr = new XMLHttpRequest();
        xhr.addEventListener("readystatechange", function() {
            if (this.readyState === 4 && this.status === 200)
                func(this.responseText, arg);
            if (this.readyState === 4 && this.status === 500)
                alert('Sorry, error.');
        });
        xhr.open("POST", "/");
        xhr.setRequestHeader("content-type", "application/json");
        xhr.send(data);
    }
    catch
    {
      alert('An error occurred.');
    }
}

export function getFunction(path, func)
{
    try
    {
        var xhr = new XMLHttpRequest();
        xhr.addEventListener("readystatechange", function() {
            if (this.readyState === 4 && this.status === 200)
                func(this.responseText);
        });
        xhr.open("GET", path);
        xhr.send();
    }
    catch
    {
      alert('An error occurred.');
    }
}

function delPicRes(val, target)
{
  var deletedPics = document.getElementsByClassName(target);
  deletedPics[0].parentNode.removeChild(deletedPics[0]);
}

function delPic() 
{
  var target = this.dataset.target;
  var data = JSON.stringify({
    "action": "deletePic",
    "target": target
  }); 
  postFunction(data, delPicRes, target)
}

export function openFocusHandler(box, key, element)
{
   box.img.addEventListener('click', function(e)
   {
       e.preventDefault();
       e.stopPropagation();
       var background = document.getElementsByClassName('column');
       if (document.getElementById('focus-img-box'))
       {
           closeFocus(background);
           return;
        }
        var newDiv = document.createElement('div');    
        newDiv.id = 'focus-img-box';
        element.append(newDiv);
        var mainDiv = document.createElement('div');    
        mainDiv.id = 'mainPicDiv';
        newDiv.appendChild(mainDiv);
        mainDiv.appendChild(box.img.cloneNode(true));
        mainDiv.children[0].id = 'mainPic';
        var divReactions = document.createElement('div');
        divReactions.className = 'reactions';
        divReactions.innerHTML = box.likesLine;
        divReactions.innerHTML += box.commentsLine;
        mainDiv.appendChild(divReactions);
        var commentsDiv = document.createElement('div');
        commentsDiv.id = 'comments';
        newDiv.appendChild(commentsDiv);
        commentsDiv.innerHTML = '<input id="commentInput" maxlength="200" placeholder="Something to say :) ? ...">';
        var data = JSON.stringify({'action' : 'getComments', 'target' : box.name});
        postFunction(data, getCommentsRes, box);    
        if (key)
        {
            divReactions.getElementsByClassName('icone')[0].addEventListener('click', function() 
            {
                var data = JSON.stringify({
                     "action" : "sendLike",
                     "target": this.dataset.target
                });
                postFunction(data, sendLike, box.name);
            })
        }
        var backgroundElem = document.getElementsByClassName('column');
        for (var i = 0; i < backgroundElem.length; i++) {
            backgroundElem[i].style.opacity = 0.2;
        }
    })
}

export function Box(name, loveNb, commentsNb, bin)
{
    this.name = name;
    this.loveNb = loveNb;
    this.commentsNb = commentsNb;
    this.bin = bin;
    this.img = document.createElement('img');
    this.img.onerror = function() {
        div.style.display = 'none';
    }
    this.img.title = this.name;
    this.img.className = 'img-box-img'
    this.img.src = 'static/gallery/' + this.name + '.png';
    this.likesLine = '<img class="icone" src="static/images/coeur.png" data-target="' + this.name + '"><span class="loveNb ' + this.name + '">' + this.loveNb + '</span>';
    this.commentsLine = '<span class="commentsNb ' + this.name + '"value="' + this.commentsNb + '">' + this.commentsNb + '</span> <span>comments</span>';
}

export function createPictureDiv(box, key, element)
{
    var div = document.createElement('div');
    div.className = 'img-box ' + box.name;
    div.appendChild(box.img);
    openFocusHandler(box, key, element);
    var divReactions = document.createElement('div');
    divReactions.className = 'reactions';
    divReactions.innerHTML = box.likesLine;
    divReactions.innerHTML += box.commentsLine;
    div.appendChild(divReactions);
    if (key)
    {
        divReactions.getElementsByClassName('icone')[0].addEventListener('click', function() 
        {
            var data = JSON.stringify({
                 "action" : "sendLike",
                 "target": this.dataset.target
            });
            postFunction(data, sendLike, box.name);
        })
    }
    else
    {
        divReactions.innerHTML += '<span class="del"><img class="icone bin" src="static/images/bin.png" data-target="' + box.name + '"></span>';
        divReactions.getElementsByClassName('bin')[0].addEventListener('click', delPic);
    }  
    return div;
}

export function checkPassword(val)
{
   var strongPassword = /^(?=.*[A-Z])(?=.*[!@#+\-\\\\\/?$&*])(?=.*[0-9])(?=.*[a-z]).{8,10}$/g;
   return (strongPassword.test(val))
}

export function containSpecialChar(val)
{
    var specialChar = /\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:|\s/g;
    return (specialChar.test(val));
}

function sendComment(val, content)
{
    var data = JSON.parse(val);
    if (data.error == 'logError')
    {
        alert('You must be logged to be able to comment posts.');
        return;
    }
    if (data.error)
    {
        alert('Sorry, an error occurred.');
        return;
    }
    var div = document.createElement('div');
    div.className = 'comment';
    div.innerHTML += '<div class="commentAuthor">' + data.username + '</div> <div class="commentContent">' + content + '</div>';
    document.getElementById('comments').appendChild(div);
    document.getElementById('commentInput').value = '';
    var commentCounts = document.getElementsByClassName('commentsNb ' + data.target);
    var newCommentsNb = commentCounts[0].innerHTML = parseInt(commentCounts[0].innerHTML) + 1;
    for (var i = 0; i < commentCounts.length; i++)
        commentCounts[i].innerHTML = newCommentsNb;   
}

export function listenComments(box) 
{
    document.getElementById('commentInput').addEventListener("keypress", function (e) 
    {
        if ((e.key == 13 || e.which == '13') && this.value)
        {
            var content = this.value;
            var data = JSON.stringify({
                "action" : "sendComment",
                "content": content,
                "target": box.name
            });
            postFunction(data, sendComment, content);
        }
    });
}

function sendLike(val, title)
{
    try
    {
        var data = JSON.parse(val);
        if (data.error == 'logError')
        {
            alert('You must be logged to be able to comment posts.');
            return;
        }
        if (data.error)
        {
            alert('Sorry, an error occurred.');
            return;
        }    
        var pic = document.getElementsByClassName('loveNb ' + title);
        var newLoveNb = pic[0].innerHTML = parseInt(pic[0].innerHTML) + 1;
        for (var i = 0; i < pic.length; i++)
            pic[i].innerHTML = newLoveNb;    
   }
    catch
    {
        alert('Sorry, error.');
    }
}

function closeFocus(background)
{
    var focusBox = document.getElementById('focus-img-box');
    if (focusBox)
    {
        focusBox.parentNode.removeChild(focusBox);
        for (var i = 0; i < background.length; i++) {
            background[i].style.opacity = 1;
          }
    }
}

function getCommentsRes(val, box)
{
   try
   {
        if (!val)
            return;
        var data = JSON.parse(val);
        if (data.error)
            return;
        for (var i = 0; i < data.length; i++)
        {
            var div = document.createElement('div');
            div.className = 'comment';
            div.innerHTML += '<div class="commentAuthor">' + data[i].username + '</div> <div class="commentContent">' + data[i].content + '</div>';
            document.getElementById('comments').appendChild(div);   
        }
        listenComments(box);
   }    
    catch(err)
    {
        alert('Error loading.');
    }      
}

export function closeFocusHandler(background)
{
    document.addEventListener('keydown', function(event) { 
    if (event.key == "Escape")
            closeFocus(background);
    for (var i = 0; i < background.length; i++) {
        background[i].addEventListener('click', function() { 
            closeFocus(background);
            })
        }
    })
}