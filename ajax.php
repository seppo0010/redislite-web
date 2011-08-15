<?php
session_start();
$id = session_id();
@mkdir('databases/' . substr($id, 0, 2));
echo shell_exec('FAKETTY=1 ./redislite/src/redislite-cli -f databases/' . substr($id, 0, 2) . '/' . substr($id, 2) . ' <<<' . escapeshellarg($_POST['command']));
