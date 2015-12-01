<?php

// verify user
session_start();
$username = $_GET['username'];

if (!isset($_SESSION['username'][$username])) {
	header("location:login.php");
}


$db = mysqli_connect("localhost","root","1234","gossip_chat");

if (!$db){
	echo '<p color="red">Connect Error. Please try again.</p>';
}

$query = "select toUser as friends from friendship where fromUser='$username' " .
		"union select fromUser from friendship where toUser='$username'";
$result = mysqli_query($db, $query);
$friends = array();
while ($row = mysqli_fetch_array($result)){
	array_push($friends, $row['friends']);
};

mysqli_close($db);


?>

<!DOCTYPE html>
<html>
<head>
	<title>Gossiping</title>
	<style>
		body {
			background-image: url("pics/bg5.jpg");
			background-size: cover;
			background-repeat: no-repeat;
		}
		div {
			border-color: #ff9966;
			font-family: "Comic Sans MS", cursive, sans-serif;
		}
		#leftDiv {
			width: 30%;
			height: 600px;
			float: left;
			border-style: ridge;
			border-color: #ff9966;
			margin-left: 100px;
			margin-top: 20px;
			position: relative;
			background-image: url("pics/pink.jpg");
			background-size: 100% 100%;
		}
		#username {
			width: 100%;
			height: 50px;
			border-bottom-style: ridge;
			background-color: #ff9966;
		}
		#welcome {
			width: 60%;
			height: 100%;
			float: left;
			padding-left: 5px;
			line-height: 50px;
			color: #800000;
		}
		#logout {
			width: 15%;
			height: 100%;
			float: right;
			padding-right: 5px;
		}
		#logoutBtn{
			border: none;
			width: 100%;
			height: 100%;
			background: none;
			font-family: "Comic Sans MS", cursive, sans-serif;
			text-align: right;
			font-style: italic;
			color: #800000;
			text-decoration: underline;
			cursor: pointer;
		}
		#friendListTitle {
			width: 100%;
			height: 30px;
			border-bottom-style: ridge;
			background-color: #ffaa80;
			text-align: center;
			color: #800000;
			line-height: 30px;
			cursor: pointer;
		}
		#friendList {
			width: 100%;
			height: 492px;
			border-bottom-style: ridge;
		}
		#addFriend {
			width: 100%;
			height: 50px;
			position: absolute;
			bottom: 0px;
			border-style: solid;
			border-width: 1px;
		}
		#addFriendBtn {
			width: 100%;
			height: 100%;
			background-color: #ff9966;
			color: #800000;
			border-style: none;
			font-family: "Comic Sans MS", cursive, sans-serif;
			font-size: 18px;
			font-weight: bold;
			cursor: pointer;
		}
		#rightDiv {
			visibility: hidden;
			width: 50%;
			height: 600px;
			float: left;
			border-style: ridge;
			border-color: #ff9966;
			margin-top: 20px;
			position: relative;
			background-color: #ffffff;
		}
		#windowHeader {
			background-color: #ff9966;
			height: 52px;
			line-height: 52px;
			text-align: center;
			color: #800000;
			font-size: 20px;
		}
		#sessionName {
			width: 80%;
			height: 52px;
			line-height: 52px;
			font-size: 20px;
			margin-right: 0px;
			float: left;
		}
		#unfriend {
			width: 15%;
			height: 100%;
			float: right;
			padding-right: 5px;
		}
		#unfriendBtn{
			border: none;
			width: 100%;
			height: 100%;
			background: none;
			font-family: "Comic Sans MS", cursive, sans-serif;
			text-align: right;
			font-style: italic;
			color: #800000;
			text-decoration: underline;
			cursor: pointer;
		}
		#content {
			width: 100%;
			height: 350px;
			overflow-y: scroll;
		}
		#typing {
			width: 100%;
			border-top-style: ridge;
			visibility: hidden;
		}
		#typingArea {
			width: 80%;
			height: 170px;
			border: none;
			margin-left: 8px;
			margin-top: 8px;
			float: left;
			font-family: "Comic Sans MS", cursive, sans-serif;
			font-size: 16px;
			color: #800000;
			border-style: dotted;
			border-color: #ffaa80;
		}
		#sendBtn {
			width: 15%;
			height: 50px;
			background-color: #ffaa80;
			margin-top: 30px;
			margin-left: 8px;
			color: #800000;
			border-style: outset;
			border-color: #ffaa80;
			font-family: "Comic Sans MS", cursive, sans-serif;
			font-size: 22px;
			line-height: 30px;
			cursor: pointer;
		}
	</style>
</head>
<body>
	<div id="leftDiv">
		<div id="username">
			<div id="welcome">Welcome, <?php echo $username?></div>
			<div id="logout"><button id="logoutBtn">Logout</button></div>
		</div>
		<div id="friendListTitle" onclick="hideRightDiv()">My Friends</div>
		<div id="friendList"></div>
		<div id="addFriend"><button id="addFriendBtn" onclick="addFriend()">Add A New Friend</button></div>
	</div>
	<div id="rightDiv">
		<div id="windowHeader">
			<div id="sessionName"></div>
			<div id="unfriend"><button id="unfriendBtn" onclick="unfriend()">Unfriend</button></div>
		</div>
		<div id="content"></div>
		<div id="typing">
			<textarea id="typingArea" type="text" name="typingArea" rows="10" maxlength="500"></textarea>
			<button id="sendBtn">Send</button>
		</div>
	</div>
	<script>
		var friends = <?php echo json_encode($friends); ?>;
		var newMessages = [];
		document.body.onload = function(){
			showFriends(friends);
			setInterval(getNewMessages, 2000);
			};

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
			var username = <?php echo json_encode($username); ?>;
			var url = "log.php";
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
				var tmpFrom = tmp["fromUser"];
				if (document.getElementById("friendDiv_" + tmpFrom) == null){
					showFriends([tmpFrom]);
				}
				var icon = document.getElementById("unread_" + tmpFrom);
				icon.style.visibility = "visible";
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
			showUnreadMessages(friendName);
		}

		function showUnreadMessages(friendName){
			for (i = 0; i < newMessages.length; i++){
				var tmp = JSON.parse(newMessages[i]);
				if (tmp["fromUser"] != friendName) {continue;}
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
				var url = "log.php";
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

		function unfriend(){
			var fromUser = "<?php echo $username?>";
			var toUser = document.getElementById("sessionName").innerHTML;
			var yes = confirm("Are you sure you want to remove " + toUser + " from your friend list?");
			if (!yes) {return;}
			var param = "fromUser=" + fromUser + "&toUser=" + toUser;
			var url = "unfriend.php?" + param;
			var ajax = new XMLHttpRequest();
			ajax.onreadystatechange = function(){
				if (ajax.readyState == 4 && ajax.status == 200){
					alert(ajax.responseText);
					removeFriend(toUser);
				}
			}
			ajax.open("GET", url, true);
			ajax.send();
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
			var username = "<?php echo $username?>";
			var param = "username=" + username;
			var url = "logout.php?" + param;
			var ajax = new XMLHttpRequest();
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					alert(ajax.responseText);
					window.location.href = "login.php";
				}
			}
			ajax.open("GET", url, true);
			ajax.send();
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
			var fromUser = "<?php echo $username?>";
			var toUser = document.getElementsByName("friendName")[0].value;
			var param = "fromUser=" + fromUser + "&toUser=" + toUser;
			var url = "add_friend.php?" + param;
			var ajax = new XMLHttpRequest();
			ajax.onreadystatechange = function() {
				if(ajax.readyState == 4) {
					alert(ajax.responseText);
					if (ajax.status == 200) {
						showFriends([toUser]);
					}
				}
			}
			ajax.open("GET", url, true);
			ajax.send();
		}

		function sendMessage(){
			var message = document.getElementById("typingArea").value;
			if (message == ""){
				alert("Please enter your message.");
			} else {
				var url = "log.php";
				var date = new Date();
				var timestamp = date.getTime();
				var fromUser = "<?php echo $username?>";
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
	</script>
</body>
</html>