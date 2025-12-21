<?php
require_once "../db.php";
include "../header.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["case"])) {

    // CASE 1: Valid dates
    if ($_POST["case"] === "1") {
        $sql = "
            INSERT INTO contract (player_id, start_date, end_date, salary)
            VALUES (1, '2025-01-01', '2025-12-31', 100000)
        ";

        if ($conn->query($sql)) {
            $msg = "✅ Case 1: Contract inserted successfully. Trigger allowed the insert.";
        } else {
            $msg = "❌ Case 1 Error: " . $conn->error;
        }
    }

    // CASE 2: Invalid dates (start_date > end_date)
    if ($_POST["case"] === "2") {
        $sql = "
            INSERT INTO contract (player_id, start_date, end_date, salary)
            VALUES (1, '2025-12-31', '2025-01-01', 100000)
        ";

        if ($conn->query($sql)) {
            $msg = "⚠️ Case 2: Insert succeeded (unexpected).";
        } else {
            $msg = "✅ Case 2: Trigger blocked the insert → " . $conn->error;
        }
    }

    // CASE 3: Edge case (equal dates)
    if ($_POST["case"] === "3") {
        $sql = "
            INSERT INTO contract (player_id, start_date, end_date, salary)
            VALUES (1, '2025-06-01', '2025-06-01', 100000)
        ";

        if ($conn->query($sql)) {
            $msg = "🟡 Case 3: Inserted with equal dates. Trigger allowed edge case.";
        } else {
            $msg = "🟡 Case 3: Trigger blocked edge case → " . $conn->error;
        }
    }
}
?>

<div style="border:1px solid #3a5bdc; padding:12px; margin-top:10px;">
  <b>Trigger 1 (by Korcan Baykal):</b>
  <span>
    This trigger validates contract dates before insertion.
    If <code>start_date &gt; end_date</code>, the trigger prevents the insert.
  </span>

  <div style="margin-top:10px;">
    <form method="POST" style="display:inline;">
      <button type="submit" name="case" value="1">Case 1</button>
    </form>

    <form method="POST" style="display:inline;">
      <button type="submit" name="case" value="2">Case 2</button>
    </form>

    <form method="POST" style="display:inline;">
      <button type="submit" name="case" value="3">Case 3</button>
    </form>
  </div>

  <?php if (!empty($msg)): ?>
    <p style="margin-top:12px;"><b>Output:</b> <?= htmlspecialchars($msg) ?></p>
  <?php endif; ?>
</div>

<br>
<a href="../index.php">Go to homepage</a>

<?php include "../footer.php"; ?>
