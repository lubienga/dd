<?php
include 'connection.php';
// BACKUP FILE FROM BOOKING TABLE

$tableName  = 'booking';
$backupFile = 'backup/booking.sql';
$query      = "SELECT * INTO OUTFILE '$backupFile' FROM $tableName";
$statement = $db->prepare($query);
$statement->execute();
$result = $statement->fetch();
$statement->closeCursor();

?>