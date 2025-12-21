<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "mongo.php";
$manager = getMongoManager();

// 1) Dropdown: only users who have at least one ACTIVE ticket
$cmd = new MongoDB\Driver\Command([
    "distinct" => "tickets",
    "key" => "username",
    "query" => ["status" => true]
]);
$usernamesCursor = $manager->executeCommand("cs306", $cmd);
$usernames = $usernamesCursor->toArray()[0]->values ?? [];

$selected = $_GET["username"] ?? "";

// 2) If username selected, list that user's ACTIVE tickets
$tickets = [];
if ($selected !== "") {
    $query = new MongoDB\Driver\Query(
        ["username" => $selected, "status" => true],
        ["sort" => ["created_at" => -1]]
    );
    $tickets = $manager->executeQuery("cs306.tickets", $query)->toArray();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Support Tickets</title></head>
<body>
  <a href="../index.php">← Back to Homepage</a>
  <h2>Support Tickets</h2>

  <form method="get" style="margin-bottom: 12px;">
    <select name="username">
      <option value="">Select user</option>
      <?php foreach ($usernames as $u): ?>
        <option value="<?= htmlspecialchars($u) ?>" <?= ($u === $selected ? "selected" : "") ?>>
          <?= htmlspecialchars($u) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit">Select</button>
  </form>

  <a href="create_ticket.php">Create a Ticket</a>

  <hr>
  <h3>Results:</h3>

  <?php if ($selected === ""): ?>
      <p>Select a username to view active tickets.</p>
  <?php elseif (count($tickets) === 0): ?>
      <p>No active tickets found for this user.</p>
  <?php else: ?>
      <?php foreach ($tickets as $t): ?>
        <div style="border:1px solid #999; padding:10px; margin:10px 0;">
          <b>Status:</b> Active<br>
          <b>Body:</b> <?= htmlspecialchars($t->message) ?><br>
          <b>Created At:</b> <?= htmlspecialchars($t->created_at) ?><br>
          <b>Username:</b> <?= htmlspecialchars($t->username) ?><br>
          
          <a href="ticket_detail.php?id=<?= (string)$t->_id ?>">View Details</a>
        </div>
      <?php endforeach; ?>
  <?php endif; ?>
</body>
</html>
