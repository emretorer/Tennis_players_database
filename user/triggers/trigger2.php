<?php
require_once "../db.php";
include "../header.php";

$msg = "";
$before = "";
$after  = "";

function esc($s) { return htmlspecialchars($s ?? ""); }

function fetchRanking(mysqli $conn, int $playerId): array {
  $stmt = $conn->prepare("SELECT * FROM ranking WHERE player_id = ? ORDER BY ranking_id DESC LIMIT 1");
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

  // Case 2 winner (ranking'de yoktu): 2
  $CASE2_WINNER = 2;

  if ($case === "1") {
    $winner = 1;
    $before = renderRow(fetchRanking($conn, $winner));

    $sql = "
      INSERT INTO matches (tournament_id, player1_id, player2_id, winner_id, score, match_date)
      VALUES (1, 1, 2, 1, '6-4 6-4', CURDATE())
    ";

    if ($conn->query($sql)) {
      $msg = "✅ Case 1: Match inserted. Trigger should UPDATE existing ranking for winner_id={$winner}.";
    } else {
      $msg = "❌ Case 1 Error: " . $conn->error;
    }

    $after = renderRow(fetchRanking($conn, $winner));
  }

  if ($case === "2") {
    $winner = $CASE2_WINNER;
    $before = renderRow(fetchRanking($conn, $winner));

    $sql = "
      INSERT INTO matches (tournament_id, player1_id, player2_id, winner_id, score, match_date)
      VALUES (1, 1, {$winner}, {$winner}, '7-5 6-3', CURDATE())
    ";

    if ($conn->query($sql)) {
      $msg = "🟡 Case 2: Match inserted. Ranking did NOT exist → trigger should CREATE ranking row then UPDATE for winner_id={$winner}.";
    } else {
      $msg = "❌ Case 2 Error: " . $conn->error;
    }

    $after = renderRow(fetchRanking($conn, $winner));
  }

  if ($case === "3") {
  // GARANTİ FAIL: invalid player1_id (FK varsa patlar)
  $winner = 1;
  $before = renderRow(fetchRanking($conn, $winner));

  $sql = "
    INSERT INTO matches (tournament_id, player1_id, player2_id, winner_id, score, match_date)
    VALUES (1, 9999, 2, 1, '0-0', CURDATE())
  ";

  try {
    $ok = $conn->query($sql);
    if ($ok) {
      $msg = "⚠️ Case 3: Insert succeeded (unexpected).";
    } else {
      $msg = "✅ Case 3: Insert blocked (expected error case) → " . $conn->error;
    }
  } catch (mysqli_sql_exception $e) {
    // Fatal yerine sayfa içine mesaj bas
    $msg = "❌ Case 3: Insert blocked (expected FK error)." ;
  }

  $after = renderRow(fetchRanking($conn, $winner));
}
}
?>

<div style="border:1px solid #3a5bdc; padding:12px; margin-top:10px;">
  <b>Trigger 2 (by Emre Törehan Törer):</b>
  Runs <b>AFTER INSERT</b> on <code>matches</code>. Updates <code>ranking</code> based on <code>winner_id</code>.
  If the winner has no ranking entry, it creates one (IF NOT EXISTS).

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
