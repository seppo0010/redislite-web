<?php
session_start();
$id = session_id();
$id = "test";
@mkdir('databases/' . substr($id, 0, 2));
$_POST['command']="GET b";
echo shell_exec('FAKETTY=1 ./redislite-cli -f databases/' . substr($id, 0, 2) . '/' . substr($id, 2) . ' <<<' . escapeshellarg($_POST['command']));
