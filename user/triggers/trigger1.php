<?php
require_once "../db.php";
include "../header.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$msg = "";

function esc($s) { return htmlspecialchars($s ?? ""); }

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["case"])) {
  $case = $_POST["case"];

  try {

    if ($case === "1") {

      $sql = "
        INSERT INTO contract (player_id, sponsor_id, start_date, end_date, amount)
        VALUES (4, 2, '2025-01-01', '2026-01-01', 50000)
      ";

      $conn->query($sql);
      $msg = "✅ Case 1: Contract inserted successfully (start_date < end_date). Trigger allows insert.";
    }

    if ($case === "2") {
      
      $sql = "
        INSERT INTO contract (player_id, sponsor_id, start_date, end_date, amount)
        VALUES (1, 1, '2026-01-01', '2025-01-01', 50000)
      ";

      $conn->query($sql);
      $msg = "⚠️ Case 2: Insert succeeded (UNEXPECTED). Trigger may not be working!";
    }

  } catch (mysqli_sql_exception $e) {

    if ($case === "2") {
      $msg = "❌ Case 2: Insert blocked as expected by Trigger 1. Error: " . $e->getMessage();
    } else {
      $msg = "❌ Error: " . $e->getMessage();
    }
  }
}
?>

<div style="border:1px solid #3a5bdc; padding:12px; margin-top:10px;">
  <b>Trigger 1 (by Korcan Baykal):</b><br>
  Runs <b>BEFORE INSERT</b> on <code>contract</code>.<br>
  Prevents inserting contracts where <code>start_date &gt; end_date</code>.

  <div style="margin-top:10px;">
    <form method="POST" style="display:inline;">
      <button type="submit" name="case" value="1">Case 1</button>
    </form>
    <form method="POST" style="display:inline;">
      <button type="submit" name="case" value="2">Case 2</button>
    </form>
  </div>

  <?php if (!empty($msg)): ?>
    <p style="margin-top:12px;"><b>Output:</b> <?= esc($msg) ?></p>
  <?php endif; ?>
</div>

<br>
<a href="../index.php">Go to homepage</a>

<?php include "../footer.php"; ?>
