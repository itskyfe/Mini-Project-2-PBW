<?php

include 'conn.php';

// Sanitasi input
$id   = (int) $_GET['id'];
$type = $_GET['type'] ?? '';

if ($type === 'exp') {
    $stmt = $conn->prepare("DELETE FROM experiences WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

if ($type === 'skill') {
    $stmt = $conn->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

if ($type === 'cert') {
    $stmt = $conn->prepare("DELETE FROM certificates WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Balik ke halaman utama setelah hapus
header("Location: index.php");
exit;