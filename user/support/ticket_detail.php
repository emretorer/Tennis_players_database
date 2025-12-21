<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "mongo.php";
$manager = getMongoManager();

$id = $_GET["id"] ?? "";
if ($id === "") die("Missing ticket id");

// FETCH TICKET
$query = new MongoDB\Driver\Query([
    "_id" => new MongoDB\BSON\ObjectId($id)
]);
$result = $manager->executeQuery("cs306.tickets", $query)->toArray();
if (count($result) === 0) die("Ticket not found");

$t = $result[0];

// HANDLE COMMENT
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["comment"])) {
    $commentText = trim($_POST["comment"]);
    $commentUser = trim($_POST["comment_user"]);

    if ($commentText !== "" && $commentUser !== "") {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update(
            ["_id" => new MongoDB\BSON\ObjectId($id)],
            [
                '$push' => [
                    'comments' => [
                        'username'   => $commentUser,
                        'message'    => $commentText,
                        'created_at' => date("Y-m-d H:i:s")
                    ]
                ]
            ]
        );
        $manager->executeBulkWrite("cs306.tickets", $bulk);
        header("Location: ticket_detail.php?id=" . $id);
        exit;
    }
}


// HANDLE DEACTIVATE
if (isset($_POST["deactivate"])) {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->update(
        ["_id" => new MongoDB\BSON\ObjectId($id)],
        ['$set' => ['status' => false]]
    );
    $manager->executeBulkWrite("cs306.tickets", $bulk);
    header("Location: tickets.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Ticket Details</title>
<style>
  .comment-box {
    border: 1px solid #0000ff;
    padding: 10px;
    margin-bottom: 10px;
  }
</style>
</head>
<body>

<form method="post">
  <button type="submit" name="deactivate">Deactivate Ticket</button>
</form>

<h2>Ticket Details</h2>

<p><b>Username:</b> <?= htmlspecialchars($t->username) ?></p>
<p><b>Body:</b> <?= htmlspecialchars($t->message) ?></p>
<p><b>Status:</b> <?= $t->status ? "Active" : "Resolved" ?></p>
<p><b>Created At:</b> <?= htmlspecialchars($t->created_at) ?></p>

<hr>

<h3>Comments:</h3>

<?php if (empty($t->comments)): ?>
  <p>No comments yet.</p>
<?php else: ?>
  <?php foreach ($t->comments as $c): ?>
    <div class="comment-box">
      <p><b>Created At:</b> <?= htmlspecialchars($c->created_at ?? "") ?></p>
      <p><b>Username:</b> <?= htmlspecialchars($c->username ?? "") ?></p>
      <p><b>Comment:</b> <?= htmlspecialchars($c->message ?? "") ?></p>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<form method="post">
  <textarea
    name="comment"
    rows="4"
    cols="50"
    placeholder="Add a comment"
    required
  ></textarea><br><br>

  <input
    type="text"
    name="comment_user"
    placeholder="Username"
    required
  ><br><br>

  <button type="submit">Add Comment</button>
</form>

<br>
<a href="ticket_list.php">Back to Tickets</a>

</body>
</html>
