// var username and var newMessages are already defined in the php page

var newMessages = [];
document.body.onload = function(){
	showFriends(friends);
	setInterval(getNewMessages, 2000);
	};

window.onbeforeunload = function(){logout();};

function showFriends(friends){
	var friendList = document.getElementById("friendList");
	for (i=0; i<friends.length; i++){
		var friendDiv = document.createElement("div");
		friendDiv.class = "friendDiv";
		friendDiv.innerHTML = friends[i];
		friendDiv.id = "friendDiv_" + friends[i];
		friendDiv.style.width = "100%";
		friendDiv.style.height = "35px";
		friendDiv.style.borderBottom = "2px solid #ff9966";
		friendDiv.style.textAlign = "center";
		friendDiv.style.lineHeight = "35px";
		friendDiv.style.color = "#800000";
		friendDiv.style.fontSize = "20px";
		friendDiv.style.cursor = "pointer";
		friendList.appendChild(friendDiv);
		friendDiv.onclick = function(){showConversation(this.textContent);};
		unread = document.createElement("img");
		unread.id = "unread_" + friends[i];
		unread.src = "pics/unread.png";
		unread.height = "10";
		unread.width = "10";
		unread.style.marginLeft = "10px";
		unread.style.visibility = "hidden";
		friendDiv.appendChild(unread);
	}
}

function getNewMessages(){
	var url = "message.php";
	var param = "?toUser=" + username + "&timeout=1";
	url = url + param;
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function() {
		if(ajax.readyState == 4 && ajax.status == 200) {
			newMessages = JSON.parse(ajax.responseText);
			showNewMessages();
			var rightDiv = document.getElementById("rightDiv");
			var typing = document.getElementById("typing");
			var friendName = document.getElementById("sessionName").innerHTML;
			if (rightDiv.style.visibility == "visible" && typing.style.visibility == "visible"){
				showUnreadMessages(friendName);
			}
		}
	};
	ajax.open("GET", url, true);
	ajax.send();
}

function showNewMessages(){
	for (i = 0; i < newMessages.length; i++){
		var tmp = JSON.parse(newMessages[i]);
		var tmpTimestamp = tmp["timestamp"];
		if (tmp["timestamp"] == "0") {
			showFriendRequest(tmp["fromUser"]);
			continue;
		}
		var tmpFrom = tmp["fromUser"];
		if (document.getElementById("friendDiv_" + tmpFrom) == null){
			showFriends([tmpFrom]);
		}
		var icon = document.getElementById("unread_" + tmpFrom);
		icon.style.visibility = "visible";
	}
}

function showFriendRequest(fromUser){
	var friendRequest = document.getElementById("friendRequest");
	friendRequest.style.visibility = "visible";
	friendRequest.onclick = function(){showRequestDetail();};
}

function showRequestDetail(){
	var rightDiv = document.getElementById("rightDiv");
	rightDiv.style.visibility = "visible";
	var typing = document.getElementById("typing");
	typing.style.visibility = "hidden";
	var sessionName = document.getElementById("sessionName");
	sessionName.innerHTML = "Friend Request";
	var unfriendBtn = document.getElementById("unfriendBtn");
	unfriendBtn.style.visibility = "hidden";
	var content = document.getElementById("content");
	clearChildNodes(content);
	for (i = 0; i < newMessages.length; i++){
		var tmp = JSON.parse(newMessages[i]);
		if (tmp["timestamp"] != "0") {
			continue;
		}
		var fromUser = tmp["fromUser"];
		var tmpRequest = document.createElement("div");
		tmpRequest.id = "requestFrom_" + fromUser;
		tmpRequest.name = "requestFrom";
		tmpRequest.innerHTML = fromUser;
		tmpRequest.style.textAlign = "left";
		tmpRequest.style.color = "#800000";
		tmpRequest.style.height = "30px";
		tmpRequest.style.lineHeight = "30px";
		tmpRequest.style.width = "80%";
		tmpRequest.style.padding = "0 30px 0 30px";
		tmpRequest.style.marginTop = "5px";
		content.appendChild(tmpRequest);
		var ignoreBtn = document.createElement("button");
		ignoreBtn.innerHTML = "Ignore";
		ignoreBtn.name = fromUser;
		ignoreBtn.onclick = function(){handleRequest(this.name, false);};
		ignoreBtn.style.marginRight = "20px";
		ignoreBtn.style.float = "right";
		ignoreBtn.style.backgroundColor = "#ffaa80";
		ignoreBtn.style.border = "solid 2px #ffaa80";
		ignoreBtn.style.height = "28px";
		ignoreBtn.style.width = "80px";
		var acceptBtn = document.createElement("button");
		acceptBtn.innerHTML = "Accept";
		acceptBtn.name = fromUser;
		acceptBtn.onclick = function(){handleRequest(this.name, true);};
		acceptBtn.style.marginRight = "30px";
		acceptBtn.style.float = "right";
		acceptBtn.style.backgroundColor = "#ffaa80";
		acceptBtn.style.border = "solid 2px #ffaa80";
		acceptBtn.style.height = "28px";
		acceptBtn.style.width = "80px";
		tmpRequest.appendChild(ignoreBtn);
		tmpRequest.appendChild(acceptBtn);
	}
}

function handleRequest(fromUser, accept){
	var url = "friend_handler.php";
	var params = "fromUser=" + fromUser + "&toUser=" + username;
	if (accept == true) {
		params = params + "&accept=1";
	} else {
		params = params + "&accept=0";
	}
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4 && ajax.status == 200) {
			if (accept == true){showFriends([fromUser]);}
			var content = document.getElementById("content");
			content.removeChild(document.getElementById("requestFrom_" + fromUser));
		}
	}
	ajax.open("POST", url, true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send(params);
	var requestFrom = document.getElementsByName("requestFrom");
	if (requestFrom.length == 0){
		var friendRequest = document.getElementById("friendRequest");
		friendRequest.style.visibility = "hidden";
	}
}

function showConversation(friendName){
	var rightDiv = document.getElementById("rightDiv");
	rightDiv.style.visibility = "visible";
	var typing = document.getElementById("typing");
	typing.style.visibility = "visible";
	var sessionName = document.getElementById("sessionName");
	sessionName.innerHTML = friendName;
	var unfriendBtn = document.getElementById("unfriendBtn");
	unfriendBtn.style.visibility = "visible";
	var content = document.getElementById("content");
	clearChildNodes(content);
	var sendBtn = document.getElementById("sendBtn");
	sendBtn.onclick = function(){sendMessage();};
	var typingArea = document.getElementById("typingArea");
	typingArea.value = "";
	var unread = document.getElementById("unread_" + friendName);
	showHistory(friendName);
	showUnreadMessages(friendName);
}

function showUnreadMessages(friendName){
	for (i = 0; i < newMessages.length; i++){
		var tmp = JSON.parse(newMessages[i]);
		if (tmp["fromUser"] != friendName || tmp["timestamp"] == "0") {continue;}
		var p = document.createElement("p");
		var tmpDate = new Date(Number(tmp["timestamp"]));
		var localTime = tmpDate.toLocaleString();
		p.innerHTML = "[" + localTime + "] " + tmp["fromUser"] + " says: " + tmp["message"];
		var content = document.getElementById("content");
		content.appendChild(p);
		p.style.width = "80%";
		p.style.marginLeft = "8px";
		p.style.marginTop = "2px";
		p.style.marginBottom = "0";
		p.style.color = "#800000";
		content.scrollTop = content.scrollHeight;
		var url = "message.php";
		var params = "setRead=1&timestamp=" + tmp["timestamp"] + "&fromUser=" + tmp["fromUser"] + "&toUser=" + tmp["toUser"];
		var ajax = new XMLHttpRequest();
		ajax.open("POST", url, true);
		ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajax.send(params);
	}
	var tmpFrom = "unread_" + friendName;
	var icon = document.getElementById(tmpFrom);
	icon.style.visibility = "hidden";
}

function showHistory(friendName){
	var fromUser = username;
	var toUser = document.getElementById("sessionName").innerHTML;
	var params = "fromUser=" + fromUser + "&toUser=" + toUser;
	var url = "history.php" + "?" + params;
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function() {
		if(ajax.readyState == 4 && ajax.status == 200) {
			var history = JSON.parse(ajax.responseText);
			for (i = 0; i < history.length; i++){
				var tmp = JSON.parse(history[i]);
				var p = document.createElement("p");
				var tmpDate = new Date(Number(tmp["timestamp"]));
				var localTime = tmpDate.toLocaleString();
				p.innerHTML = "[" + localTime + "] " + tmp["fromUser"] + " says: " + tmp["message"];
				var content = document.getElementById("content");
				content.appendChild(p);
				p.style.width = "80%";
				p.style.marginLeft = "8px";
				p.style.marginTop = "2px";
				p.style.marginBottom = "0";
				p.style.color = "#800000";
				content.scrollTop = content.scrollHeight;
			}
		}
	};
	ajax.open("GET", url, false);
	ajax.send();
}

function unfriend(){
	var fromUser = username;
	var toUser = document.getElementById("sessionName").innerHTML;
	var yes = confirm("Are you sure you want to remove " + toUser + " from your friend list?");
	if (!yes) {return;}
	var params = "fromUser=" + fromUser + "&toUser=" + toUser;
	var url = "unfriend.php";
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4 && ajax.status == 200){
			alert(ajax.responseText);
			removeFriend(toUser);
		}
	}
	ajax.open("POST", url, true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send(params);
}

function removeFriend(friendName){
	var friendList = document.getElementById("friendList");
	var friendDiv = document.getElementById("friendDiv_" + friendName);
	friendList.removeChild(friendDiv);
	hideRightDiv();
}

function hideRightDiv(){
	var rightDiv = document.getElementById("rightDiv");
	rightDiv.style.visibility = "hidden";
	var typing = document.getElementById("typing");
	typing.style.visibility = "hidden";
	var unfriendBtn = document.getElementById("unfriendBtn");
	unfriendBtn.style.visibility = "hidden";
}

var logoutBtn = document.getElementById("logoutBtn");
logoutBtn.onclick = function(){logout();};

function logout(){
	var param = "username=" + username;
	var url = "logout.php";
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			alert(ajax.responseText);
			window.location.href = "login.php";
		}
	}
	ajax.open("POST", url, false);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send(param);
}

function addFriend(){
	var rightDiv = document.getElementById("rightDiv");
	rightDiv.style.visibility = "visible";
	var typing = document.getElementById("typing");
	typing.style.visibility = "hidden";
	var sessionName = document.getElementById("sessionName");
	sessionName.innerHTML = "Add A New Friend";
	var unfriendBtn = document.getElementById("unfriendBtn");
	unfriendBtn.style.visibility = "hidden";
	var friendSearch = document.createElement("form");
	friendSearch.method = "post";
	friendSearch.id = "friendSearch";
	friendSearch.action = "";
	friendSearch.onsubmit = function(){requestSent();};
	friendSearch.style.height = "270px";
	friendSearch.innerHTML = "Please enter your friend's username.";
	friendSearch.style.padding = "80px 0 0 120px";
	friendSearch.style.color = "#800000";
	var content = document.getElementById("content");
	clearChildNodes(content);
	content.appendChild(friendSearch);
	var enterFriend = document.createElement("input");
	enterFriend.type = "text";
	enterFriend.name = "friendName";
	enterFriend.maxLength = 20;
	enterFriend.style.height = "30px";
	enterFriend.style.position = "absolute";
	enterFriend.style.left = "20%";
	enterFriend.style.top = "30%";
	enterFriend.style.color = "#800000";
	enterFriend.style.fontFamily = '"Comic Sans MS", cursive, sans-serif';
	enterFriend.style.border = "2px groove #ff884d";
	friendSearch.appendChild(enterFriend);
	var friendSubmit = document.createElement("input");
	friendSubmit.id = "friendSubmit";
	friendSubmit.type = "button";
	friendSubmit.onclick = function(){requestSent();};
	friendSubmit.name = "friendSubmit";
	friendSubmit.value = "Send Request";
	friendSubmit.style.height = "36px";
	friendSubmit.style.position = "absolute";
	friendSubmit.style.right = "25%";
	friendSubmit.style.top = "30%";
	friendSubmit.style.width = "150px";
	friendSubmit.style.backgroundColor = "#ff884d";
	friendSubmit.style.color = "#800000";
	friendSubmit.style.border = "5px solid #ff884d";
	friendSubmit.style.fontFamily = '"Comic Sans MS", cursive, sans-serif';
	friendSubmit.style.fontWeight = "bold";
	friendSearch.appendChild(friendSubmit);
}

function requestSent(){
	var fromUser = username;
	var toUser = document.getElementsByName("friendName")[0].value;
	var params = "fromUser=" + fromUser + "&toUser=" + toUser;
	var url = "add_friend.php";
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function() {
		if(ajax.readyState == 4) {
			alert(ajax.responseText);
		}
	}
	ajax.open("POST", url, true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send(params);
}

function sendMessage(){
	var message = document.getElementById("typingArea").value;
	if (message == ""){
		alert("Please enter your message.");
	} else {
		var url = "message.php";
		var date = new Date();
		var timestamp = date.getTime();
		var fromUser = username;
		var toUser = document.getElementById("sessionName").innerHTML;
		var params = "timestamp=" + timestamp + "&fromUser=" + fromUser + "&toUser=" + toUser + "&message=" + message;
		var ajax = new XMLHttpRequest();
		ajax.onreadystatechange = function() {
			if(ajax.readyState == 4 && ajax.status == 200) {
				var localTime = date.toLocaleString();
				var p = document.createElement("p");
				p.innerHTML = "[" + localTime + "] " + fromUser + " says: " + message;
				var content = document.getElementById("content");
				content.appendChild(p);
				p.style.width = "80%";
				p.style.marginLeft = "8px";
				p.style.marginTop = "2px";
				p.style.marginBottom = "0";
				p.style.color = "#800000";
				var content = document.getElementById("content");
				content.scrollTop = content.scrollHeight;
			} else if(ajax.readyState == 4 && ajax.status == 400){
				alert(ajax.responseText);
				removeFriend(toUser);
			}
		}
		ajax.open("POST", url, true);
		ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajax.send(params);
		var typingArea = document.getElementById("typingArea");
		typingArea.value = "";
	}
}

function clearChildNodes(node){
	while (node.firstChild){
		node.removeChild(node.firstChild);
	}
}
