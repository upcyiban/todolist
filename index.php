<?php
include 'iapp.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no;">
    <title>To Do</title>
    <link href="main.css" rel="stylesheet">
    <script>
		var _hmt = _hmt || [];
		(function() {
 		var hm = document.createElement("script");
  		hm.src = "//hm.baidu.com/hm.js?16a67cb11ac6cccdcba39185ff45a1fb";
  		var s = document.getElementsByTagName("script")[0]; 
  		s.parentNode.insertBefore(hm, s);
		})();
	</script>
</head>
<body>
<div id="error" class="error hide-d">
    <h1>少年，咱们换个浏览器吧！</h1>

    <p class="sta"><a href="#">去下载Chrome</a></p>
</div>
<div id="container" class="container">
    <h1>To Do List</h1>

    <p class="sta">
        <span id="remaining">0</span>
        of
        <span id="all">0</span>
        remaining
    </p>

    <p class="sta pointer clean" onclick="clean()">clean</p>

    <div class="width-ctrl">
        <form id="add-task" class="add-task" name="addTaskForm" onsubmit="return false">
            <input type="text" class="new-task-inp" name="newTaskInp" value="">
            <input type="submit" class="add-task-btn pointer" name="addTaskBtn" value="发射！">
        </form>
        <div id="warm" class="warm"></div>
        <ul id="task-list" class="task-list"></ul>
    </div>

    <footer>
        <p>Powered by <a href="http://www.sevenskey.xyz/365/index.html">Sevenskey</a> & <a href="http://yb.upc.edu.cn">UPCyiban</a>
        </p>

        <p>要吃鱼啊喵(^=ω=^)</p>
    </footer>
</div>

<script type="text/javascript">
    window.onload = function () {
        restoreData();
        document.forms.addTaskForm.newTaskInp.focus();
        addTask();
    }
    function storeData() {
        if (window.localStorage) {
            window.localStorage.listData = document.getElementById('task-list').innerHTML;
            window.localStorage.remaining = document.getElementById('remaining').innerHTML;
            window.localStorage.allTask = document.getElementById('all').innerHTML;
        }
    }
    function restoreData() {
        if (window.localStorage) {
            if (localStorage.listData && localStorage.remaining && localStorage.allTask) {
                document.getElementById('remaining').innerHTML = localStorage.remaining;
                document.getElementById('all').innerHTML = localStorage.allTask;
                document.getElementById('task-list').innerHTML = localStorage.listData;
            }
        } else {
            setClass(document.getElementById('container'), 'hide-d');
            removeClass(document.getElementById('error'), 'hide-d');
        }
    }
    function checkInp() {
        var newTask = document.forms.addTaskForm.newTaskInp.value;
        var warmDiv = document.getElementById('warm');

        setTimeout("addClass(document.getElementById('warm'), 'hide-d');removeClass(document.getElementById('warm'), 'show-d');", 1000);

        if (newTask.length > 7) {
            warmDiv.innerHTML = '输入要少于7个字哟QWQ';
            addClass(warmDiv, 'show-d');
            removeClass(warmDiv, 'hide-d');
            return false;
        } else if (newTask.length == 0) {
            warmDiv.innerHTML = '你忘记输入内容了哟QWQ';
            addClass(warmDiv, 'show-d');
            removeClass(warmDiv, 'hide-d');
            return false;
        } else {
            addClass(warmDiv, 'hide-d');
            removeClass(warmDiv, 'show-d');
            return true;
        }
    }
    function clean() {
        var taskList = document.getElementById('task-list');
        var liList = taskList.childNodes;
        var delArr = new Array();

        for (var i = 0; i < liList.length; i++) {
            if (liList[i].className.indexOf('del-li') != -1) {
                delArr.push(liList[i]);
            }
        }
        for (i = 0; i < delArr.length; i++) {
            taskList.removeChild(delArr[i]);
        }
        statistics();
        storeData();
    }
    function addTask() {
        var tastAddFormObj = document.forms.addTaskForm;
        var addTaskBtnObj = tastAddFormObj.addTaskBtn;
        var taskList = document.getElementById('task-list');

        addTaskBtnObj.onclick = function () {
            var newTask = tastAddFormObj.newTaskInp.value;
            if (checkInp()) {
                var ANewLi = document.createElement('li');
                var ANewCheckBox = document.createElement('input');
                var ANewLabel = document.createElement('label');
                var ANewTaskText = document.createTextNode(newTask);
                ANewCheckBox.type = 'checkbox';
                ANewLi.setAttribute('onclick', 'delATask(this)');

                ANewLi.appendChild(ANewCheckBox);
                ANewLabel.appendChild(ANewTaskText);
                ANewLi.appendChild(ANewLabel);
                taskList.insertBefore(ANewLi, taskList.childNodes[0]);

                tastAddFormObj.newTaskInp.value = '';

                statistics();
                storeData();
            }
        }
    }
    function delATask(present) {
        var checkbox = present.childNodes[0];
        var label = present.childNodes[1];

        if (checkbox.checked == false) {
            setClass(label, 'del');
            setClass(present, 'del-li');
            checkbox.setAttribute('checked', 'checked');
        } else {
            setClass(label, 'notdel');
            removeClass(present, 'del-li');
            checkbox.removeAttribute('checked');
        }
        statistics();
        storeData();
    }
    function statistics() {
        var taskListObj = document.getElementById('task-list');
        var nodeList = taskListObj.childNodes;
        var liList = new Array();
        var a = 0;

        for (var i = 0; i < nodeList.length; i++) {
            if (nodeList[i].nodeType == 1) {
                liList.push(nodeList[i]);
            }
        }
        for (i = 0; i < liList.length; i++) {
            if (liList[i].getElementsByTagName('label')[0].className == 'del') {
                a += 1;
            }
        }
        document.getElementById('remaining').innerHTML = liList.length - a;
        document.getElementById('all').innerHTML = liList.length;
    }
    /*function sibingElem(elem){
     var _node = new Array(),
     _elem = elem;
     while(_elem = _elem.previousSibling){
     if (_elem.nodeType == 1){
     _node.push(_elem);
     break;
     };
     }
     _elem = elem;
     while(_elem = _elem.nextSibling){
     if(_elem.nodeType == 1){
     _node.push(_elem);
     break;
     }
     }
     return _node;
     }*/
    function removeClass(elem, oldClass) {
        var classList = elem.className.split(' ');
        for (var i = 0; i < classList.length; i++) {
            if (classList[i].indexOf(oldClass) != -1) {
                classList.splice(i, 1);
            }
        }
        elem.className = classList.join(' ');
        //console.log(classList);
    }
    function addClass(elem, newClass) {
        if (elem.className.indexOf(newClass) == -1) {
            elem.className += (' ' + newClass);
        }
    }
    function setClass(elem, newClass) {
        elem.setAttribute('class', newClass);
    }
</script>
</body>
</html>