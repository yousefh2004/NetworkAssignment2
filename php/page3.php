<?php
require_once 'header.php';

$ip = "127.0.0.1";
$community = "public";
$base = ".1.3.6.1.2.1.11";

echo '<link rel="stylesheet" href="../css/p3.css">';
echo '<div class="page">';

$snmpObjects = [
1=>["InPkts", "Received"],2=>["OutPkts", "Sent"],3=> ["BadVersions", "Bad version"],4=> ["BadCommNames", "Bad community"],
5=> ["BadCommUses", "Wrong use"],6=> ["ParseErrs", "Parse error"],8=> ["InTooBigs", "Too big"],9=> ["InNoSuchNames", "No such name"],
10=> ["InBadValues", "Bad value"],11=> ["InReadOnlys", "Read only"],12=> ["InGenErrs", "Gen error"],13=> ["ReqVars", "Requested vars"],
14=> ["SetVars", "Set vars"],15=> ["GetReqs", "Get req"],16=> ["GetNexts", "GetNext req"],17=> ["SetReqs", "Set req"],
18=> ["GetResps", "Get resp"],19=> ["InTraps", "Trap in"],20=> ["OutTooBigs", "Too big"],21=> ["OutNoSuchNames", "No such name"],
22=> ["OutBadValues", "Bad value"],24=> ["OutGenErrs", "Gen error"],25=> ["OutGetReqs", "Get req"],26=> ["OutGetNexts", "GetNext req"],
27=> ["OutSetReqs", "Set req"],28=> ["OutGetResps", "Get resp"],29=> ["OutTraps", "Trap out"],30=> ["AuthTraps", "Auth traps"]
];

print("<div class='section'><div class='section-title'>Method 1 : snmp2_get()</div>");

echo "<table>";
echo "<tr><th>X</th><th>OID</th><th>Name</th><th>Description</th><th>Value</th></tr>";

foreach ($snmpObjects as $x => $obj) {
    $oid = $base . "." . $x . ".0";
    $name = $obj[0];
    $desc = $obj[1];
    $value = snmp2_get($ip, $community, $oid);

    echo "<tr>";
    echo "<td>$x</td>";
    echo "<td>$oid</td>";
    echo "<td>$name</td>";
    echo "<td>$desc</td>";
    echo "<td>$value</td>";
    echo "</tr>";
}
echo "</table></div>";

print("<div class='section'><div class='section-title'>Method 2 : snmp2_walk()</div>");

$walkValues = snmp2_walk($ip, $community, $base);

echo "<table>";
echo "<tr><th>X</th><th>Name</th><th>Description</th><th>Value</th></tr>";

$i = 0;
$keys = array_keys($snmpObjects);

foreach ($walkValues as $val) {
    $x = $keys[$i];
    $name = $snmpObjects[$x][0];
    $desc = $snmpObjects[$x][1];

    echo "<tr>";
    echo "<td>$x</td>";
    echo "<td>$name</td>";
    echo "<td>$desc</td>";
    echo "<td>$val</td>";
    echo "</tr>";

    $i++;
}
echo "</table></div>";


require_once 'footer.php';
?>