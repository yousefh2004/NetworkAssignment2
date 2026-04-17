<?php
$ip = "127.0.0.1";
snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    snmp2_set($ip, "public", ".1.3.6.1.2.1.1.4.0", "s", $_POST['sysContact']);
    snmp2_set($ip, "public", ".1.3.6.1.2.1.1.5.0", "s", $_POST['sysName']);
    snmp2_set($ip, "public", ".1.3.6.1.2.1.1.6.0", "s", $_POST['sysLocation']);
    echo "<script>alert('Saved successfully');</script>";
}
$sysContact  = snmp2_get($ip, "public", ".1.3.6.1.2.1.1.4.0");
$sysName     = snmp2_get($ip, "public", ".1.3.6.1.2.1.1.5.0");
$sysLocation = snmp2_get($ip, "public", ".1.3.6.1.2.1.1.6.0");
?>
<?php require_once 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>System Info Yousef & Mohammad </title>
     <link rel="stylesheet" href="../css/p1.css">
</head>
<body>
<div class="container">
<h2 class="title">System Information Yousef & Mohammad</h2>
<table class="table">
<tr><td>sysContact</td><td>.1.3.6.1.2.1.1.4.0</td><td><?php echo $sysContact; ?></td></tr>
<tr><td>sysName</td><td>.1.3.6.1.2.1.1.5.0</td><td><?php echo $sysName; ?></td></tr>
<tr><td>sysLocation</td><td>.1.3.6.1.2.1.1.6.0</td><td><?php echo $sysLocation; ?></td></tr>
</table>

<div class="form">
<form method="POST">
    sysContact:<br>
    <input class="input" type="text" name="sysContact" value="<?php echo $sysContact; ?>"><br>
    sysName:<br>
    <input class="input" type="text" name="sysName" value="<?php echo $sysName; ?>"><br>
    sysLocation:<br>
    <input class="input" type="text" name="sysLocation" value="<?php echo $sysLocation; ?>"><br>
    <input class="btn" type="submit" value="Save">
</form>
</div>

</div>

</body>
</html>
<?php $page = 1; require_once 'footer.php'; ?>