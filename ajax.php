<?php

include 'conn.php';

$type = $_POST['type'] ?? '';

// ── TAMBAH EXPERIENCE ───
if ($type === 'add_exp') {
    $stmt = $conn->prepare("INSERT INTO experiences (role, year) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST['role'], $_POST['year']);
    $stmt->execute();

    // Kembalikan data yang baru dimasukin
    echo json_encode([
        'id'   => $conn->insert_id,
        'role' => $_POST['role'],
        'year' => $_POST['year'],
    ]);
}

// ── TAMBAH SKILL ───
if ($type === 'add_skill') {
    $stmt = $conn->prepare("INSERT INTO skills (name, pct) VALUES (?, ?)");
    $stmt->bind_param("si", $_POST['name'], $_POST['pct']);
    $stmt->execute();

    echo json_encode([
        'id'   => $conn->insert_id,
        'name' => $_POST['name'],
        'pct'  => $_POST['pct'],
    ]);
}

// ── UPLOAD SERTIFIKAT ───
if ($type === 'add_cert') {
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
    $maxSize      = 2 * 1024 * 1024; // 2MB

    $fileName = $_FILES['file']['name'];
    $tmpPath  = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validasi tipe file
    if (!in_array($ext, $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Format file tidak didukung. Gunakan JPG, PNG, atau WebP.']);
        exit;
    }

    // Validasi ukuran file
    if ($fileSize > $maxSize) {
        http_response_code(400);
        echo json_encode(['error' => 'Ukuran file maksimal 2MB.']);
        exit;
    }

    $newName = time() . '_' . $fileName;
    move_uploaded_file($tmpPath, 'upload/' . $newName);

    $stmt = $conn->prepare("INSERT INTO certificates (title, tag, file) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['title'], $_POST['tag'], $newName);
    $stmt->execute();

    echo json_encode([
        'id'    => $conn->insert_id,
        'title' => $_POST['title'],
        'tag'   => $_POST['tag'],
        'file'  => $newName,
    ]);
}

// ── HAPUS ITEM ──
if ($type === 'delete') {
    // Whitelist tabel yang boleh dihapus
    $allowedTables = ['experiences', 'skills', 'certificates'];
    $table         = $_POST['table'];

    if (!in_array($table, $allowedTables)) {
        http_response_code(400);
        echo json_encode(['error' => 'Tabel tidak valid.']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
}

// ── EDIT EXPERIENCE ──
if ($type === 'edit_exp') {
    $stmt = $conn->prepare("UPDATE experiences SET role = ?, year = ? WHERE id = ?");
    $stmt->bind_param("ssi", $_POST['role'], $_POST['year'], $_POST['id']);
    $stmt->execute();
}

// ── EDIT SKILL ──
if ($type === 'edit_skill') {
    $stmt = $conn->prepare("UPDATE skills SET name = ?, pct = ? WHERE id = ?");
    $stmt->bind_param("sii", $_POST['name'], $_POST['pct'], $_POST['id']);
    $stmt->execute();
}