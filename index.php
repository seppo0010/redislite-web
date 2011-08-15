<?php
session_start();
$session_id = session_id();
?>
<html>
<head>
<title>Redislite Demo</title>
<style>
h1 {
	font: 26px/30px Helvetica, Arial, sans-serif;
}
p {
	font: 12px/14px Helvetica, Arial, sans-serif;
}
#box { 
	font: 12px/20px Menlo,"monospace";
	/*
	height: 300px;
	overflow: auto;
	*/
	float: left;
}

#box .prompt {
	color: #666;
}

#box .query {
	font-weight: bold;
}

#box input {
	margin: 0;
	padding: 0;
	border: 0;
	font: bold 12px/20px Menlo,"monospace";
	width: 500px;
}

#available_commands {
	float: right;
	border: 1px solid #000;
	padding: 4px;
	font: 18px/24px Helvetica, Arial, sans-serif;
}

#available_commands ul,
#available_commands ul li {
	list-style: none;
	margin: 0;
	padding: 0;
	border: 0;
	font: bold 12px/20px Menlo,"monospace";
	text-transform:uppercase;
}

#available_commands ul li {
	padding: 0 4px;
}
#available_commands ul li a {
	color: #222;
	text-decoration: none;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.2/prototype.js"></script>
<script type="text/javascript">
var available_commands = ["get","set","setnx","append","strlen","del","exists","getbit","getrange","substr","incr","decr","mget","rpush","lpush","rpushx","lpushx","rpop","lpop","llen","lindex","lrange","incrby","decrby","rename","renamenx","keys","dbsize","ping","type","info"];

var commands = [];
var cursor = -1;
var has_temp_data = false;

Event.observe(document, 'click', function() { $('command').focus(); });
Event.observe(window, 'load', function() {
	(function () {
		var list = new Element('ul');
		$A(available_commands).each(function (c) {
			list.insert(new Element('li').insert(new Element('a', {"href":"http://redis.io/commands/" + c, "target": "_blank"}).update(c)));
		});
		$('available_commands').insert(list);
	})()
	Event.observe($('command'), 'keypress', function(event) {
		if(event.keyCode == Event.KEY_RETURN) {
			var command = new Element('div', {"class":"command"}).insert(
				new Element('span', {"class": "prompt"}).update('redislite>')
			).insert(
				new Element('span', {"class": "query"}).update(' ' + $F('command').escapeHTML())
			);
			$("prompt").insert({"before":command});
			command.hide();
			new Ajax.Request('ajax.php', {
				"method": "post",
				"parameters": { "command" : $F('command') },
				"onSuccess": function(t) {
					command.insert(new Element('div', {"class": "result"}).update(t.responseText.escapeHTML().replace(/\n/g, "<br />")));
				},
				"onFailure": function(t) {
					command.insert(new Element('div', {"class": "result"}).update("(error) Response error"));
				},
				"onComplete": function() {
					command.show();
					cursor = -1;
					$('command').focus();
					try {
						$(document).scrollTop = $(document).scrollHeight;
					} catch (e) {}
				},
			});
			if (has_temp_data) {
				has_temp_data = false;
				commands.pop();
			}
			commands.push($F('command'));
			$('command').setValue('');
		} else if (event.keyCode == Event.KEY_UP) {
			event.stop();
			if (cursor == -1) {
				cursor = commands.length + 0;
				if ($F('command').length > 0) {
					has_temp_data = true;
					commands.push($F('command'));
				}
			}
			cursor--;
			if (cursor < 0) return;
			$('command').setValue(commands[cursor].escapeHTML());
		} else if (event.keyCode == Event.KEY_DOWN) {
			event.stop();
			if (cursor == -1) return;
			if (cursor >= commands.length - 1) {
				 if (has_temp_data) {
					 has_temp_data = false;
					 commands.pop();
				 }
				$('command').setValue('');
				cursor = -1;
				return;
			 }
			cursor++;
			$('command').setValue(commands[cursor].escapeHTML());
		}
	});
	$('command').focus();
});
</script>
</head>
<body>
<div id="available_commands">
	<h2>Quick<br />Reference</h2>
</div>
<h1>Redislite Demo</h1>
<p>Redislite is a software library that implements a self-contained, serverless, zero-configuration, redis-compatible database engine. Like SQLite is to a SQL server.<br />Feel free to try it out!</p>
<p>You can also <a href="http://github.com/seppo0010/redislite">grab the source code</a> and <a href="databases/<?php echo substr($session_id, 0, 2); ?>/<?php echo substr($session_id,2); ?>.rld">download your database</a></p>
<div id="box">
	<div class="command">
	<span class="prompt">redislite&gt;</span>
	<span class="query">SET key value</span>
	</div>
	<div class="result">
	OK
	</div>

	<div class="command">
	<span class="prompt">redislite&gt;</span>
	<span class="query">GET key</span>
	</div>
	<div class="result">
	"value"
	</div>

	<div class="command">
	<span class="prompt">redislite&gt;</span>
	<span class="query">DEL key</span>
	</div>
	<div class="result">
	(integer) 1
	</div>
	<div class="command" id="prompt">
		<span class="prompt">redislite&gt;</span>
		<input type="text" spellcheck="false" autocomplete="off" id="command" />
	</div>
</div>
</body>
</html>
