<?php session_start(); ?>
<html>
<head>
<title>Redislite Demo</title>
<style>
#box { 
	font: 12px/20px Menlo,"monospace";
	height: 300px;
	overflow: auto;
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
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.2/prototype.js"></script>
<script type="text/javascript">
Event.observe(window, 'load', function() {
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
					command.insert(new Element('div', {"class": "result"}).update(t.responseText.escapeHTML()));
				},
				"onFailure": function(t) {
					command.insert(new Element('div', {"class": "result"}).update("(error) Response error"));
				},
				"onComplete": function() {
					command.show();
				},
			});
			$('command').setValue('');
		}
	});
	$('command').focus();
});
</script>
</head>
<h1>Redislite Demo</h1>
<div id="box">
	<div class="command">
	<span class="prompt">redislite&gt;</span>
	<span class="query">GET a</span>
	</div>
	<div class="result">
	"asd"
	</div>
	<div class="command" id="prompt">
		<span class="prompt">redislite&gt;</span>
		<input type="text" spellcheck="false" autocomplete="off" id="command" />
	</div>
</div>
</html>
