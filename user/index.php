<?php
include "header.php";
?>

<h1>User Homepage</h1>

<hr>

<h2>Triggers:</h2>

<div style="border:1px solid #000; padding:10px; margin-bottom:20px;">

  <div style="border:1px solid #3a5bdc; padding:10px; margin:10px 0;">
    <b>Trigger 1 (by Korcan Baykal):</b>
    Validates contract dates and prevents invalid or overlapping contracts.
    <br>
    <a href="triggers/trigger1.php">Go to the trigger's page</a>
  </div>

  <div style="border:1px solid #3a5bdc; padding:10px; margin:10px 0;">
    <b>Trigger 2 (by Emre Törehan Törer):</b>
    Updates the winner’s ranking automatically after a match is inserted.
    <br>
    <a href="triggers/trigger2.php">Go to the trigger's page</a>
  </div>

  <div style="border:1px solid #3a5bdc; padding:10px; margin:10px 0;">
    <b>Trigger 3 (by Aslı Koturoğlu):</b>
    Automatically creates a ranking record when a new player is added.
    <br>
    <a href="triggers/trigger3.php">Go to the trigger's page</a>
  </div>

</div>

<hr>

<h2>Stored Procedures:</h2>

<div style="border:1px solid #000; padding:10px; margin-bottom:20px;">

  <div style="border:1px solid #3a5bdc; padding:10px; margin:10px 0;">
    <b>Stored Procedure 1 (by Korcan Baykal):</b>
    Inserts a new match and triggers automatic ranking updates.
    <br>
    <a href="procedures/proc1.php">Go to the procedure's page</a>
  </div>

  <div style="border:1px solid #3a5bdc; padding:10px; margin:10px 0;">
    <b>Stored Procedure 2 (by Emre Törehan Törer):</b>
    Adds a new contract while enforcing contract validity rules.
    <br>
    <a href="procedures/proc2.php">Go to the procedure's page</a>
  </div>

  <div style="border:1px solid #3a5bdc; padding:10px; margin:10px 0;">
    <b>Stored Procedure 3 (by Aslı Koturoğlu):</b>
    Retrieves and displays an overview of a player’s information.
    <br>
    <a href="procedures/proc3.php">Go to the procedure's page</a>
  </div>

</div>

<hr>

<a href="support/ticket_list.php">Support Page</a>
<br><br>

<?php
include "footer.php";
?>
