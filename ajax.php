<?php
if (get_magic_quotes_gpc()) $_POST['command'] = stripslashes($_POST['command']);
session_start();
$id = session_id();
@mkdir('databases/' . substr($id, 0, 2));
echo shell_exec('FAKETTY=1 ./redislite/src/redislite-cli -f databases/' . substr($id, 0, 2) . '/' . substr($id, 2) . '.rld <<<' . escapeshellarg($_POST['command']));
