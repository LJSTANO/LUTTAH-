<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "contributions_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $amount = $_POST["amount"];
    $date = date("Y-m-d");

    $stmt = $conn->prepare("INSERT INTO contributions (name, amount, date) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $amount, $date);
    $stmt->execute();
    $stmt->close();
}

// Fetch contributions
$contributions = $conn->query("SELECT * FROM contributions ORDER BY id DESC");
$total = 0;
$data = [];
while ($row = $contributions->fetch_assoc()) {
    $total += $row['amount'];
    $data[] = $row;
}
$target = 60000;
$percent = min(($total / $target) * 100, 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mama Luttah Contribution</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #e9f0fa; padding: 20px; }
    .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #2a5d9f; }
    .progress { background: #cce0f5; border-radius: 5px; overflow: hidden; margin: 10px 0; }
    .progress-bar { background: #2a5d9f; height: 20px; width: <?= floor($percent) ?>%; color: white; text-align: center; transition: width 0.5s; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; }
    th, td { border: 1px solid #bdd7ee; padding: 10px; text-align: left; }
    th { background-color: #d6eaf8; }
    form input[type="text"], form input[type="number"] {
      padding: 8px; margin: 5px; width: calc(50% - 10px); border: 1px solid #ccc; border-radius: 5px;
    }
    form input[type="submit"] {
      padding: 8px 16px; background-color: #2a5d9f; color: white; border: none; border-radius: 5px; cursor: pointer;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Mama Luttah Contribution</h2>
  <p><strong>Total Raised:</strong> Ksh <strong><?= $total ?></strong> /60,000</p>
  <div class="progress"><div class="progress-bar"><?= floor($percent) ?>%</div></div>

  <form method="POST" action="">
    <input type="text" name="name" placeholder="Contributor Name" required />
    <input type="number" name="amount" placeholder="Amount (Ksh)" required />
    <input type="submit" value="Add Contribution" />
  </form>

  <table>
    <thead><tr><th>Name</th><th>Amount</th><th>Date</th></tr></thead>
    <tbody>
      <?php foreach ($data as $entry): ?>
      <tr>
        <td><?= htmlspecialchars($entry['name']) ?></td>
        <td><?= $entry['amount'] ?></td>
        <td><?= $entry['date'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
