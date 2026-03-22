<?php

include 'conn.php';

$type = $_POST['type'] ?? '';

// ── TAMBAH EXPERIENCE ──
if ($type === 'exp') {
    $stmt = $conn->prepare("INSERT INTO experiences (role, year) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST['role'], $_POST['year']);
    $stmt->execute();
}

// ── TAMBAH SKILL ───
if ($type === 'skill') {
    $pct  = (int) $_POST['pct'];
    $stmt = $conn->prepare("INSERT INTO skills (name, pct) VALUES (?, ?)");
    $stmt->bind_param("si", $_POST['name'], $pct);
    $stmt->execute();
}

// ── UPLOAD SERTIFIKAT ───
if ($type === 'cert') {
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
    $maxSize      = 2 * 1024 * 1024; // 2MB

    $fileName = $_FILES['file']['name'];
    $tmpPath  = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validasi tipe file
    if (!in_array($ext, $allowedTypes)) {
        die("File harus berupa gambar (JPG, PNG, WebP)!");
    }

    // Validasi ukuran file
    if ($fileSize > $maxSize) {
        die("Ukuran file maksimal 2MB!");
    }

    // Kasih prefix timestamp
    $newName = time() . '_' . $fileName;
    move_uploaded_file($tmpPath, 'upload/' . $newName);

    $stmt = $conn->prepare("INSERT INTO certificates (title, tag, file) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['title'], $_POST['tag'], $newName);
    $stmt->execute();
}

header("Location: index.php");
exit;