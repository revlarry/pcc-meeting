<?php
// Settings for a database connection on an online server or localhost
// Determine on which server you are working to proceed.

// Determine on which server you are working to proceed.
if ($_SERVER['SERVER_NAME'] == 'localhost') {
 
  // Create connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pcc";
 
}
else {
  // Create connection
$servername = "pccmeetings.db.8687452.5fc.hostedresource.net";
$username = "pccmeetings";
$password = "ifYlkd1966@";
$dbname = "pccmeetings";
 
  }

    ?>