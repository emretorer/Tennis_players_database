<?php include "../header.php"; ?>

<h2>Stored Procedures</h2>

<ul>
  <li>
    <a href="proc1.php">
      sp_add_match
    </a> – Inserts a match and updates ranking (Trigger 2).
  </li>

  <li>
    <a href="proc2.php">
      sp_add_contract
    </a> – Inserts a contract, prevents overlap (Trigger 1).
  </li>

  <li>
    <a href="proc3.php">
      sp_get_player_overview
    </a> – Displays player overview information.
  </li>
</ul>

<br>
<a href="../index.php">Go to homepage</a>

<?php include "../footer.php"; ?>
