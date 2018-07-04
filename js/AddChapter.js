function onKeyPress(e) {
    if(e.keyCode == 13) {
        addChapter();
        return false;
    }
}

function addChapter() {

	var input_number = document.getElementById('input-chapter-number');
	var input_name = document.getElementById('input-chapter-name');

	var comic_id = getParameterByName("id", window.location.href);
	var chapter_number = input_number.value;
	var chapter_name = input_name.value;

	var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
    	var span = document.getElementById("add-error")
        if (this.readyState == 4 && this.status == 200) {
            // spanError.innerHTML = this.response;
            span.classList.remove("alert-warning");
            span.classList.add("alert-success");
            span.innerHTML = "Success add chapter";
            span.style.visibility = "visible";

            // location.reload();
        }
        else {
            span.classList.remove("alert-success");
            span.classList.add("alert-warning");
            span.innerHTML = "Fail to add chapter";
            span.style.visibility = "visible";
        }
    }
    if(!comic_id || !chapter_number || !chapter_name) {
        var span = document.getElementById('add-error');
        span.classList.remove("alert-success");
        span.classList.add("alert-alert");
        span.innerHTML = "Invalid Chapter Number/Chapter Name";
        span.style.visibility = "visible";
        return;
    }
    var url = "/api/Comic/Chapter/Add.php?id=" + comic_id + "&number=" + chapter_number + "&name=" + chapter_name; 
    xmlHttp.open("POST", url , true);
    xmlHttp.send();
}

function retrieveAllChapters() {
	var comic_id = getParameterByName("id", window.location.href);

	// console.log(comic_id);
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var obj = JSON.parse(this.response);
            // console.log(obj);
            for(var element in obj) {
                var target = obj[element];
                appendTable(target['chapter_number'], target['chapter_name']);
            }
        }
    }
    xmlHttp.open("GET", "/api/Comic/Chapter/All.php?id=" + comic_id, true);
    xmlHttp.send();
}

function appendTable(chapter_number, chapter_name) {
	var chapter_table = document.getElementById("chapter-table");

	var row = chapter_table.insertRow(chapter_table.rows.length);

	var cell_number = row.insertCell(0);
	var cell_name = row.insertCell(1);	

	cell_number.innerHTML = chapter_number;
	cell_number.align = "center";
	cell_name.innerHTML = chapter_name;
	cell_name.align = "center";
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}