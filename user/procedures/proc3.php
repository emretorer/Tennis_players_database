<?php
require_once "../db.php";
include "../header.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
function esc($s){ return htmlspecialchars((string)($s ?? "")); }

$msg = "";
$tableHtml = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $player_id = (int)($_POST["player_id"] ?? 1);

  try {
    $stmt = $conn->prepare("CALL sp_get_player_overview(?)");
    $stmt->bind_param("i", $player_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
      $row = $res->fetch_assoc();
      $msg = "✅ sp_get_player_overview returned 1 row.";

      $tableHtml .= "<table border='1' cellpadding='6' style='border-collapse:collapse;margin-top:10px;'>";
      foreach ($row as $k => $v) {
        $tableHtml .= "<tr><th style='text-align:left;background:#f2f2f2;'>".esc($k)."</th><td>".esc($v)."</td></tr>";
      }
      $tableHtml .= "</table>";
    } else {
      $msg = "🟡 No data returned for player_id={$player_id}.";
    }

    $stmt->close();
  } catch (mysqli_sql_exception $e) {
    $msg = "❌ Error: " . $e->getMessage();
  }
}
?>

<div style="border:1px solid #3a5bdc; padding:12px; margin-top:10px;">
  <b>Stored Procedure: sp_get_player_overview (by Aslı Koturoğlu)</b><br>
  Returns an overview of a player (player info + related fields + ranking points summary).

  <form method="POST" style="margin-top:10px; display:flex; gap:10px; align-items:flex-end;">
    <label>Player ID
      <input type="number" name="player_id" value="<?= esc($_POST["player_id"] ?? 1) ?>">
    </label>
    <button type="submit">Call Procedure</button>
  </form>

  <?php if ($msg): ?>
    <p style="margin-top:12px;"><b>Output:</b> <?= esc($msg) ?></p>
  <?php endif; ?>

  <?= $tableHtml ?>
</div>

<br>
<a href="../index.php">Go to homepage</a>
<?php include "../footer.php"; ?>
