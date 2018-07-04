function addNewPagesInput() {
    var $newRow = $("#page-input").clone();
    $newRow.children(".input-row").children(".page-number-input").val("");
    $newRow.children(".input-row").children(".page-url-input").val("");
    $newRow.appendTo("#page-input-table");

    // var container = document.getElementById("page-input-table");
    // var existingInput = document.getElementById("page-input");
    // var newInput = existingInput.cloneNode(true);
    // //cln.id = "";
    // container.appendChild(newInput);
}

function getAllAnswer() {
    var comic_id = 0;
    var chapter_id = 0;
    var allPages = new Array();
    var allURL = new Array();
    var file_length = new Array();

    var allPageInput = document.getElementsByClassName("page-number-input");
    for (i = 0; i < allPageInput.length; i++) {
        if (allPageInput[i].value) {
            allPages.push(allPageInput[i].value);
        }
    }

    var allURLInput = document.getElementsByClassName("page-url-input");
    for (i = 0; i < allURLInput.length; i++) {
        if (allURLInput[i].value) {
            allURL.push(allURLInput[i].value);
        }
    }

    //get the comic id
    var comic_dropdown = document.getElementById("comic-select");
    var comic_id = comic_dropdown.options[comic_dropdown.selectedIndex].value;


    //get chapter id
    var chapter_dropdown = document.getElementById("chapter-select");
    if(chapter_dropdown.selectedIndex == -1) return;
    var chapter_id = chapter_dropdown.options[chapter_dropdown.selectedIndex].value;

    // //get file length, after this finished post everything
    // var finished = 0;
    // for(var i = 0; i<allURL.length; i++) {
    //     httpGetAsync(allURL[i], function(file_length) {
    //         file_length.push(file_length);
    //         finished++;
    //         console.log(file_length);
    //         if(finished >= allURL.length) {
                
    //         }
    //     });
    // }

    var object = {
        comic_id: comic_id,
        chapter_id: chapter_id, 
        pages: allPages,
        url: allURL,
        // file_length: file_length
    }

    console.log(object);

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //mau diapakan responsenya
            var response = this.response;
            console.log(response);
            var span = document.getElementById("notification");
            span.innerHTML = "Pages added";
            span.classList.remove("alert-warning");
            span.classList.add("alert-success");
            span.style.visibility = 'visible';
        }
        else {
            var span = document.getElementById("notification");
            span.style.visibility = 'visible';
            span.classList.remove("alert-success");
            span.classList.add("alert-warning");
            span.innerHTML = "Pages failed to be added";
        }
    }
    xmlHttp.open("POST", "Add.php", true);
    xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlHttp.send(serialize(object));

    //comic_id=4&chapter_id=40&pages=1%2C2%2C3&url=a%2Cb%2Cc

    // post('Add.php', object);

}

function serialize(obj) {
  var str = [];
  for (var p in obj)
    if (obj.hasOwnProperty(p)) {
      str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
    }
  return str.join("&");
}

function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    console.log(form);
    form.submit();
}

function httpGetAsync(theUrl, callback)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            // callback(xmlHttp.responseText);
            callback(xmlHttp.getResponseHeader('Content-Length'));
    }
    console.log(theUrl);
    xmlHttp.open("GET", theUrl, true); // true for asynchronous 
    xmlHttp.send(null);
}

function retrieveAllComic() {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //mau diapakan responsenya
            var response = this.response;
        }
    }
    xmlHttp.open("GET", "api/Comic/All", true);
    xmlHttp.send();
}

function retrieveAllChapters() {
    var dropdown = document.getElementById("comic-select");
    var comic_id = dropdown.options[dropdown.selectedIndex].value;

    if(comic_id < 1) return;

    //bersihkan dulu semua yang ada di dalam select
    var chapter_dropdown = document.getElementById("chapter-select");
    var length = chapter_dropdown.options.length;
    for(i = length; i >= 0; i--) {
        chapter_dropdown.options[i] = null;
    }

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var obj = JSON.parse(this.response);
            //console.log(obj);
            for(var element in obj) {
                var target = obj[element];
                addOption(target['chapter_id'], target['chapter_name'], target['chapter_number']);
            }
        }
    }
    xmlHttp.open("GET", "/api/Comic/Chapter/All.php?id=" + comic_id, true);
    xmlHttp.send();
}

function addOption(chapter_id, chapter_name, chapter_number) {
    var dropdown = document.getElementById("chapter-select");
    var option = document.createElement("option");
    option.text = chapter_number + " - " + chapter_name;
    option.value = chapter_id;
    // console.log(option);
    dropdown.add(option);
}

// https://www.html5rocks.com/en/tutorials/cors/