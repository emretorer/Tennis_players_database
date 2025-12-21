<?php
require_once "../db.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    if ($_POST["case"] === "A") {
      $conn->query("
        INSERT INTO contract (player_id, sponsor_id, start_date, end_date, amount)
        VALUES (1, 1, '2024-01-01', '2025-01-01', 50000)
      ");
      $msg = "CASE A: Valid contract inserted.";
    }

    if ($_POST["case"] === "B") {
      $conn->query("
        INSERT INTO contract (player_id, sponsor_id, start_date, end_date, amount)
        VALUES (1, 1, '2025-01-01', '2024-01-01', 50000)
      ");
      $msg = "CASE B should NOT succeed (check trigger).";
    }
  } catch (Throwable $e) {
    $msg = "TRIGGER ERROR: " . $e->getMessage();
  }
}
?>
<!doctype html>
<html>
<body>
<h2>Trigger 2 – Contract Date Validation</h2>

<form method="post">
  <button name="case" value="A">Valid Contract</button>
  <button name="case" value="B">Invalid Contract</button>
</form>

<p><b><?php echo $msg; ?></b></p>
<a href="index.php">Back</a>
</body>
</html>
