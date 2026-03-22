<?php
include 'conn.php';

$type = $_POST['type'] ?? '';

// ADD EXP
if($type=="add_exp"){
$stmt=$conn->prepare("INSERT INTO experiences(role,year) VALUES(?,?)");
$stmt->bind_param("ss",$_POST['role'],$_POST['year']);
$stmt->execute();

echo json_encode([
"id"=>$conn->insert_id,
"role"=>$_POST['role'],
"year"=>$_POST['year']
]);
}

// ADD SKILL
if($type=="add_skill"){
$stmt=$conn->prepare("INSERT INTO skills(name,pct) VALUES(?,?)");
$stmt->bind_param("si",$_POST['name'],$_POST['pct']);
$stmt->execute();

echo json_encode([
"id"=>$conn->insert_id,
"name"=>$_POST['name'],
"pct"=>$_POST['pct']
]);
}

// ADD CERT
if($type=="add_cert"){
$file=$_FILES['file']['name'];
$tmp=$_FILES['file']['tmp_name'];

move_uploaded_file($tmp,"upload/".$file);

$stmt=$conn->prepare("INSERT INTO certificates(title,tag,file) VALUES(?,?,?)");
$stmt->bind_param("sss",$_POST['title'],$_POST['tag'],$file);
$stmt->execute();

echo json_encode([
"id"=>$conn->insert_id,
"title"=>$_POST['title'],
"file"=>$file
]);
}

// DELETE
if($type=="delete"){
$stmt=$conn->prepare("DELETE FROM ".$_POST['table']." WHERE id=?");
$stmt->bind_param("i",$_POST['id']);
$stmt->execute();
}

// EDIT
if($type=="edit_exp"){
$stmt=$conn->prepare("UPDATE experiences SET role=?,year=? WHERE id=?");
$stmt->bind_param("ssi",$_POST['role'],$_POST['year'],$_POST['id']);
$stmt->execute();
}

if($type=="edit_skill"){
$stmt=$conn->prepare("UPDATE skills SET name=?,pct=? WHERE id=?");
$stmt->bind_param("sii",$_POST['name'],$_POST['pct'],$_POST['id']);
$stmt->execute();
}