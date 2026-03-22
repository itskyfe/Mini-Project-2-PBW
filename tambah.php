<?php
include 'conn.php';

$type = $_POST['type'];

if($type == "exp"){
    $stmt = $conn->prepare("INSERT INTO experiences(role, year) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST['role'], $_POST['year']);
    $stmt->execute();
}

if($type == "skill"){
    $pct = (int) $_POST['pct'];
    $stmt = $conn->prepare("INSERT INTO skills(name, pct) VALUES (?, ?)");
    $stmt->bind_param("si", $_POST['name'], $pct);
    $stmt->execute();
}

if($type == "cert"){

    $allowed = ['jpg','jpeg','png','webp'];

    $fileName = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        die("File harus gambar!");
    }

    if($_FILES['file']['size'] > 2000000){
        die("Max 2MB!");
    }

    $newName = time() . "_" . $fileName;
    move_uploaded_file($tmp, "upload/".$newName);

    $stmt = $conn->prepare("INSERT INTO certificates(title, tag, file) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['title'], $_POST['tag'], $newName);
    $stmt->execute();
}

header("Location: index.php");