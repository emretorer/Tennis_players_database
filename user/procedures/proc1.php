<?php
require_once "../db.php";
include "../header.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function esc($s){ return htmlspecialchars((string)($s ?? "")); }

$msg = "";
$out = "";
$afterRanking = "";

function fetchRanking(mysqli $conn, int $playerId): array {
  $stmt = $conn->prepare("SELECT ranking_id, player_id, ranking_points, rank_position, ranking_date
                          FROM ranking WHERE player_id = ? ORDER BY ranking_id DESC LIMIT 1");
  $stmt->bind_param("i", $playerId);
  $stmt->execute();
  $res = $stmt->get_result();
  $row = $res ? $res->fetch_assoc() : null;
  $stmt->close();
  return $row ?: [];
}

function renderRow(array $row): string {
  if (!$row) return "<i>(no row)</i>";
  return "<pre style='background:#f7f7f7;border:1px solid #ccc;padding:10px;'>".esc(print_r($row,true))."</pre>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $tournament_id = (int)($_POST["tournament_id"] ?? 1);
  $player1_id    = (int)($_POST["player1_id"] ?? 1);
  $player2_id    = (int)($_POST["player2_id"] ?? 2);
  $winner_id     = (int)($_POST["winner_id"] ?? 1);
  $score         = trim($_POST["score"] ?? "6-4 6-4");
  $match_date    = $_POST["match_date"] ?? date("Y-m-d");

  try {
    $stmt = $conn->prepare("CALL sp_add_match(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiss", $tournament_id, $player1_id, $player2_id, $winner_id, $score, $match_date);
    $stmt->execute();

    $msg = "✅ sp_add_match called successfully. (INSERT procedure; empty result set is expected.)";

    $afterRanking = renderRow(fetchRanking($conn, $winner_id));

    $stmt->close();
  } catch (mysqli_sql_exception $e) {
    $msg = "❌ Error: " . $e->getMessage();
  }
}
?>

<div style="border:1px solid #3a5bdc; padding:12px; margin-top:10px;">
  <b>Stored Procedure: sp_add_match (by Korcan Baykal)</b><br>
  Inserts a match into <code>matches</code>. After insertion, Trigger 2 updates <code>ranking</code> (points + rank_position).

  <form method="POST" style="margin-top:10px; display:grid; gap:8px; max-width:420px;">
    <label>Tournament ID <input type="number" name="tournament_id" value="<?= esc($_POST["tournament_id"] ?? 1) ?>"></label>
    <label>Player 1 ID   <input type="number" name="player1_id" value="<?= esc($_POST["player1_id"] ?? 1) ?>"></label>
    <label>Player 2 ID   <input type="number" name="player2_id" value="<?= esc($_POST["player2_id"] ?? 2) ?>"></label>
    <label>Winner ID     <input type="number" name="winner_id" value="<?= esc($_POST["winner_id"] ?? 1) ?>"></label>
    <label>Score         <input type="text"   name="score" value="<?= esc($_POST["score"] ?? "6-4 6-4") ?>"></label>
    <label>Match Date    <input type="date"   name="match_date" value="<?= esc($_POST["match_date"] ?? date("Y-m-d")) ?>"></label>
    <button type="submit">Call Procedure</button>
  </form>

  <?php if ($msg): ?>
    <p style="margin-top:12px;"><b>Output:</b> <?= esc($msg) ?></p>
  <?php endif; ?>

  <?php if ($afterRanking): ?>
    <hr>
    <p><b>Ranking row AFTER (winner_id):</b></p>
    <?= $afterRanking ?>
  <?php endif; ?>
</div>

<br>
<a href="../index.php">Go to homepage</a>
<?php include "../footer.php"; ?>
