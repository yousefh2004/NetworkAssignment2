<?php
require_once 'header.php';
snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
$ip = "127.0.0.1";
echo '<link rel="stylesheet" href="../css/p3.css">';
?>

<div class="page">
<div class="section">
<div class="section-title">Method 1 : snmp2_get()</div>
<table>
<tr>
    <th>Number</th>
    <th>OID</th>
    <th>Name</th>
    <th>Description</th>
    <th>Value</th>
</tr>
<?php
$data = [
1=>["InPkts","Received"],2=>["OutPkts","Sent"],3=>["BadVersions","Bad version"],4=>["BadCommNames","Bad community"],
5=>["BadCommUses","Wrong use"],6=>["ParseErrs","Parse error"],8=>["InTooBigs","Too big"],9=>["InNoSuchNames","No such name"],
10=>["InBadValues","Bad value"],11=>["InReadOnlys","Read only"],12=>["InGenErrs","Gen error"],13=>["ReqVars","Requested vars"],
14=>["SetVars","Set vars"],15=>["GetReqs","Get req"],16=>["GetNexts","GetNext req"],17=>["SetReqs","Set req"],
18=>["GetResps","Get resp"],19=>["InTraps","Trap in"],20=>["OutTooBigs","Too big"],21=>["OutNoSuchNames","No such name"],
22=>["OutBadValues","Bad value"],24=>["OutGenErrs","Gen error"],25=>["OutGetReqs","Get req"],26=>["OutGetNexts","GetNext req"],
27=>["OutSetReqs","Set req"],28=>["OutGetResps","Get resp"],29=>["OutTraps","Trap out"],30=>["AuthTraps","Auth traps"]
];
foreach ($data as $n => $i) {
    $oid = ".1.3.6.1.2.1.11.$n.0";
    echo "<tr>";
    echo "<td>$n</td>";
    echo "<td>$oid</td>";
    echo "<td>{$i[0]}</td>";
    echo "<td>{$i[1]}</td>";
    echo "<td>" . snmp2_get($ip, "public", $oid) . "</td>";
    echo "</tr>";
}
?>
</table>
</div>
<div class="section">
<div class="section-title">Method 2 : snmp2_walk()</div>
<table>
<tr>
    <th>Number</th>
    <th>Name</th>
    <th>Description</th>
    <th>Value</th>
</tr>
<?php
$walk = snmp2_walk($ip, "public", ".1.3.6.1.2.1.11");
$k = array_keys($data);
foreach ($walk as $i => $value) {
    $x = $k[$i];
    echo "<tr>";
    echo "<td>$x</td>";
    echo "<td>{$data[$x][0]}</td>";
    echo "<td>{$data[$x][1]}</td>";
    echo "<td>$value</td>";
    echo "</tr>";
}
?>
</table>
</div>
</div>
<?php require_once 'footer.php'; ?>