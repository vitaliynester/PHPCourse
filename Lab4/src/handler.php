<?php

use Lab4\Database;

include_once 'Database.php';

$db = new Database();
if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    echo json_encode($db->getValueFromDbPDO(['id' => $userId]));
} elseif (isset($_POST['query'])) {
    $query = $_POST['query'];
    echo json_encode($db->getValueFromDbInjection($query));
} elseif (isset($_POST['add_to_db'])) {
    $db->addRowsInDb();
    echo true;
}
