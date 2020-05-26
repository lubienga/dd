<?php 
       include 'menu.inc';
	 ?>
<?php
if(count($_POST)>0) {
	require_once("db.php");
	$sql = "INSERT INTO invoice ( inv_date, client_id_num, consultation) VALUES ('" . $_POST["inv_date"] . "','" . $_POST["client_id_num"] . "','" . $_POST["consultation"] .  "')";
	mysqli_query($conn,$sql);
	$current_id = mysqli_insert_id($conn);
	if(!empty($current_id)) {
		$message = "New invoice Added Successfully";
	}
}
?>
<html>
<head>
<title>Add New Invoice</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>

 <div class="container">
            
            <div class="span10 offset1">
			
<form name="frmUser" method="post" action="">
<div style="width:500px;">
<div class="message"><?php if(isset($message)) { echo $message; } ?></div>
<div align="right" style="padding-bottom:5px;"><a href="manager_invoice.php" class="link"><img alt='List' title='List' src='images/list.png' width='15px' height='15px'/> List User</a></div>
<table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="2">Add New Invoice</td>
</tr>
<tr>
<td><label>Invoice Number</label></td>
<td><input type="text" name="userName" class="txtField"></td>
</tr>
<tr>
<td><label>Invoice Date</label></td>
<td><input type="date" name="date" class="txtField"></td>
</tr>
<td><label>Client ID N#</label></td>
<td><input type="text" name="firstName" class="txtField"></td>
</tr>
<td><label>Consultation Fee</label></td>
<td><input type="text" name="fee" class="txtField"></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
</tr>
</table>
</div>
</form>
</body></html>