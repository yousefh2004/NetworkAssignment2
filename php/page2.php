<?php
require_once 'header.php';

$ip = "127.0.0.1";
$community = "public";

echo '<link rel="stylesheet" href="../css/p2.css">';
echo '<div class="page">';

print("<div class='section'><div class='section-title'>UDP Statistics Fields</div>");

echo "<table>";
echo "<tr><th>#</th><th>Object Name</th><th>OID</th><th>Value</th><th>Description</th></tr>";
echo "<tr><td>1</td><td>udpInDatagrams</td><td>.1.3.6.1.2.1.7.1.0</td><td>".snmp2_get($ip, $community, '.1.3.6.1.2.1.7.1.0')."</td><td>Total datagrams delivered to UDP users</td></tr>";
echo "<tr><td>2</td><td>udpNoPorts</td><td>.1.3.6.1.2.1.7.2.0</td><td>".snmp2_get($ip, $community, '.1.3.6.1.2.1.7.2.0')."</td><td>Datagrams with no application at destination port</td></tr>";
echo "<tr><td>3</td><td>udpInErrors</td><td>.1.3.6.1.2.1.7.3.0</td><td>".snmp2_get($ip, $community, '.1.3.6.1.2.1.7.3.0')."</td><td>Datagrams that could not be delivered</td></tr>";
echo "<tr><td>4</td><td>udpOutDatagrams</td><td>.1.3.6.1.2.1.7.4.0</td><td>".snmp2_get($ip, $community, '.1.3.6.1.2.1.7.4.0')."</td><td>Total datagrams sent from this entity</td></tr>";
echo "</table></div>";

print("<div class='section'><div class='section-title'>UDP Table</div>");

$addrs = snmp2_walk($ip, $community, '.1.3.6.1.2.1.7.5.1.1');
$ports = snmp2_walk($ip, $community, '.1.3.6.1.2.1.7.5.1.2');

echo "<table>";
echo "<tr><th>#</th><th>Local Address</th><th>Local Port</th></tr>";

if (!$addrs || count($addrs) == 0) {
    echo "<tr><td colspan='3'>No UDP listener entries found</td></tr>";
} else {
    for ($i = 0; $i < count($addrs); $i++) {
        echo "<tr>";
        echo "<td>".($i + 1)."</td>";
        echo "<td>".$addrs[$i]."</td>";
        echo "<td>".($ports[$i] ?? 'N/A')."</td>";
        echo "</tr>";
    }
}
echo "</table></div>";

require_once 'footer.php';
?>