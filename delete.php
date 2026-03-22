<?php
include 'conn.php';

$id = (int) $_GET['id'];
$type = $_GET['type'];

if($type == "exp"){
    $conn->query("DELETE FROM experiences WHERE id=$id");
}

if($type == "skill"){
    $conn->query("DELETE FROM skills WHERE id=$id");
}

if($type == "cert"){
    $conn->query("DELETE FROM certificates WHERE id=$id");
}

header("Location: index.php?admin=1");