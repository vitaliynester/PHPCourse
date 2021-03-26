<?php

require_once "TaskA.php";

$inputs = glob("tests/TaskA/*.dat");
$outputs = glob("tests/TaskA/*.ans");

$taskA = new TaskA();

$it = 1;
foreach (array_combine($inputs, $outputs) as $input => $output) {
    echo "<p>TEST #" . $it . "\t";
    $taskA->checkTask($input, $output);
    echo "</p>";
    $it++;
}