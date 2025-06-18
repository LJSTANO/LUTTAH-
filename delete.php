<?php
include 'db.php';

$id = $_POST['id'];
$conn->query("DELETE FROM contributions WHERE id = $id");
?>
