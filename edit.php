<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$amount = $_POST['amount'];

$stmt = $conn->prepare("UPDATE contributions SET name = ?, amount = ? WHERE id = ?");
$stmt->bind_param("sii", $name, $amount, $id);
$stmt->execute();
?>
