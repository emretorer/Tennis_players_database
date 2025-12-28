<?php
require_once "../db.php";
include "../header.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$msg = "";
$before = "";
$after  = "";

function esc($s) { return htmlspecialchars($s ?? ""); }

function fetchRanking(mysqli $conn, int $playerId): array {
  $stmt = $conn->prepare(
    "SELECT * FROM ranking 
     WHERE player_id = ? 
     ORDER BY ranking_id DESC 
     LIMIT 1"
  );
  $stmt->bind_param("i", $playerId);
  $stmt->execute();
  $res = $stmt->get_result();
  $row = $res ? $res->fetch_assoc() : null;
  $stmt->close();
  return $row ? $row : [];
}

function renderRow(array $row): string {
  return "<pre style='background:#f7f7f7;border:1px solid #ccc;padding:10px;'>"
       . esc(print_r($row, true))
       . "</pre>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["case"])) {
  $case = $_POST["case"];

  if ($case === "1" || $case === "2") {

   
    $before = renderRow([]);

   
    if ($case === "1") {
      $first = "Trigger3";
      $last  = "PlayerOne";
      $date  = "2000-01-01";
    } else {
      $first = "Trigger3";
      $last  = "PlayerTwo";
      $date  = "2001-02-02";
    }

    $sql = "
      INSERT INTO player (first_name, last_name, birth_date)
      VALUES (?, ?, ?)
    ";

    try {
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sss", $first, $last, $date);
      $stmt->execute();
      $stmt->close();

      $newPlayerId = (int)$conn->insert_id;

     
      $afterRow = fetchRanking($conn, $newPlayerId);
      $after = renderRow($afterRow);

      if (!empty($afterRow)) {
        $msg = "✅ Case {$case}: Player inserted (player_id={$newPlayerId}). Trigger 3 executed → ranking row CREATED automatically.";
      } else {
        $msg = "❌ Case {$case}: Player inserted (player_id={$newPlayerId}) but ranking row NOT found. Trigger may not be working correctly.";
      }

    } catch (mysqli_sql_exception $e) {
      $msg = "❌ Case {$case} Exception: " . esc($e->getMessage());
      $after = renderRow([]);
    }
  }
}
?>

<div style="border:1px solid #3a5bdc; padding:12px; margin-top:10px;">
  <b>Trigger 3 (by Aslı Koturoğlu):</b>
  Runs <b>AFTER INSERT</b> on <code>player</code>.
  Automatically creates a corresponding <code>ranking</code> row for each newly inserted player.

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

  <?php if (!empty($before) || !empty($after)): ?>
    <hr>
    <p><b>Ranking row BEFORE:</b></p>
    <?= $before ?>
    <p><b>Ranking row AFTER:</b></p>
    <?= $after ?>
  <?php endif; ?>
</div>

<br>
<a href="../index.php">Go to homepage</a>

<?php include "../footer.php"; ?>
