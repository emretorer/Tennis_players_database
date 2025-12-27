<?php
require_once "../db.php";
include "../header.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
function esc($s){ return htmlspecialchars((string)($s ?? "")); }

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $player_id  = (int)($_POST["player_id"] ?? 1);
  $sponsor_id = (int)($_POST["sponsor_id"] ?? 1);
  $start_date = $_POST["start_date"] ?? "2025-01-01";
  $end_date   = $_POST["end_date"]   ?? "2026-01-01";
  $amount     = (float)($_POST["amount"] ?? 50000);

  try {
    $stmt = $conn->prepare("CALL sp_add_contract(?, ?, ?, ?, ?)");

    $stmt->bind_param("iissd", $player_id, $sponsor_id, $start_date, $end_date, $amount);
    $stmt->execute();
    $stmt->close();

    $msg = "✅ sp_add_contract called successfully. (If dates/overlap invalid, DB will block via SIGNAL/Trigger 1.)";
  } catch (mysqli_sql_exception $e) {
    $msg = "❌ Error: " . $e->getMessage();
  }
}
?>

<div style="border:1px solid #3a5bdc; padding:12px; margin-top:10px;">
  <b>Stored Procedure: sp_add_contract (by Emre Törehan Törer)</b><br>
  Inserts a contract into <code>contract</code>. Prevents overlapping contracts (same player+sponsor). Trigger 1 also enforces date validity.

  <form method="POST" style="margin-top:10px; display:grid; gap:8px; max-width:420px;">
    <label>Player ID   <input type="number" name="player_id" value="<?= esc($_POST["player_id"] ?? 1) ?>"></label>
    <label>Sponsor ID  <input type="number" name="sponsor_id" value="<?= esc($_POST["sponsor_id"] ?? 1) ?>"></label>
    <label>Start Date  <input type="date"   name="start_date" value="<?= esc($_POST["start_date"] ?? "2025-01-01") ?>"></label>
    <label>End Date    <input type="date"   name="end_date" value="<?= esc($_POST["end_date"] ?? "2026-01-01") ?>"></label>
    <label>Amount      <input type="number" step="0.01" name="amount" value="<?= esc($_POST["amount"] ?? "50000") ?>"></label>
    <button type="submit">Call Procedure</button>
  </form>

  <?php if ($msg): ?>
    <p style="margin-top:12px;"><b>Output:</b> <?= esc($msg) ?></p>
  <?php endif; ?>
</div>

<br>
<a href="../index.php">Go to homepage</a>
<?php include "../footer.php"; ?>
