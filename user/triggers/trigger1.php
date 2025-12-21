<?php
require_once "../db.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    if ($_POST["case"] === "A") {
      $sql = "
        INSERT INTO matches (tournament_id, player1_id, player2_id, winner_id, score, match_date)
        VALUES (1, 1, 2, 1, '6-4 6-4', CURDATE())
      ";
      $conn->query($sql);
      $msg = "CASE A: Match inserted, trigger executed.";
    }

    if ($_POST["case"] === "B") {
      $sql = "
        INSERT INTO matches (tournament_id, player1_id, player2_id, winner_id, score, match_date)
        VALUES (1, 1, 2, 2, '7-6 6-7 7-5', CURDATE())
      ";
      $conn->query($sql);
      $msg = "CASE B: Different winner, trigger executed.";
    }
  } catch (Throwable $e) {
    $msg = "ERROR: " . $e->getMessage();
  }
}
?>
<!doctype html>
<html>
<body>
<h2>Trigger 1 – Match Insert</h2>

<form method="post">
  <button name="case" value="A">Run Case A</button>
  <button name="case" value="B">Run Case B</button>
</form>

<p><b><?php echo $msg; ?></b></p>
<a href="index.php">Back</a>
</body>
</html>
