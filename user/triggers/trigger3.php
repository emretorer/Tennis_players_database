<?php
require_once "../db.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    $conn->query("
      INSERT INTO player (first_name, last_name, birth_date, gender)
      VALUES ('Test', 'Player', '2000-01-01', 'M')
    ");
    $msg = "Player inserted, ranking row auto-created by trigger.";
  } catch (Throwable $e) {
    $msg = "ERROR: " . $e->getMessage();
  }
}
?>
<!doctype html>
<html>
<body>
<h2>Trigger 3 – Player Insert</h2>

<form method="post">
  <button type="submit">Insert Player</button>
</form>

<p><b><?php echo $msg; ?></b></p>
<a href="index.php">Back</a>
</body>
</html>
