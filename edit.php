<?php
include 'conn.php';

$id = (int) $_POST['id'];

if(!empty($_POST['role'])){
    $stmt = $conn->prepare("UPDATE experiences SET role=?, year=? WHERE id=?");
    $stmt->bind_param("ssi", $_POST['role'], $_POST['year'], $id);
    $stmt->execute();
}

if(!empty($_POST['name'])){
    $pct = (int) $_POST['pct'];
    $stmt = $conn->prepare("UPDATE skills SET name=?, pct=? WHERE id=?");
    $stmt->bind_param("sii", $_POST['name'], $pct, $id);
    $stmt->execute();
}

header("Location: index.php?admin=1");