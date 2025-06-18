<?php
include 'db.php';

$name = $_POST['name'];
$amount = $_POST['amount'];
$date = $_POST['date'];

$stmt = $conn->prepare("INSERT INTO contributions (name, amount, date) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $name, $amount, $date);
$stmt->execute();

echo "Success";
?>
