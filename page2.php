<?php
require_once 'shared_header.php';

$ip = $SNMP_IP;
$ro = $SNMP_COMMUNITY_RO;

// UDP Statistics
$udpStats = [
    'udpInDatagrams'  => ['.1.3.6.1.2.1.7.1.0', 'Total datagrams delivered to UDP users'],
    'udpNoPorts'      => ['.1.3.6.1.2.1.7.2.0', 'Datagrams with no application at destination port'],
    'udpInErrors'     => ['.1.3.6.1.2.1.7.3.0', 'Datagrams that could not be delivered'],
    'udpOutDatagrams' => ['.1.3.6.1.2.1.7.4.0', 'Total datagrams sent from this entity'],
];

$statValues = [];
foreach ($udpStats as $name => [$oid]) {
    $statValues[$name] = snmp_get_safe($ip, $ro, $oid);
}

// UDP Table
$udpTableData = [];
$addrs = array_values(snmp_walk_safe($ip, $ro, '.1.3.6.1.2.1.7.5.1.1'));
$ports = array_values(snmp_walk_safe($ip, $ro, '.1.3.6.1.2.1.7.5.1.2'));
for ($i = 0; $i < count($addrs); $i++) {
    $udpTableData[] = [
        'addr' => snmp_val($addrs[$i] ?? 'N/A'),
        'port' => snmp_val($ports[$i] ?? 'N/A'),
    ];
}

page_header('Page 2 — UDP Group', 'page2');
?>

<div class="wrapper">
    <div class="page-title">
        <h1>Page 2 — UDP Group</h1>
        <p>OID: 1.3.6.1.2.1.7 &nbsp;|&nbsp; Agent: <?= htmlspecialchars($ip) ?> &nbsp;|&nbsp; Community: <?= htmlspecialchars($ro) ?></p>
    </div>

    <!-- STAT BOXES -->
    <div class="stat-row">
        <?php
        $colors = ['udpInDatagrams'=>'#1a6a3a','udpNoPorts'=>'#8a5000','udpInErrors'=>'#8a1a1a','udpOutDatagrams'=>'#1a3a6a'];
        foreach ($udpStats as $name => [$oid, $desc]):
            $val = snmp_val($statValues[$name]);
            $col = $colors[$name] ?? '#1a3a6a';
        ?>
        <div class="stat-box" style="border-top-color:<?= $col ?>">
            <div class="num" style="color:<?= $col ?>"><?= htmlspecialchars($val) ?></div>
            <div class="lbl"><?= htmlspecialchars($name) ?></div>
            <div class="desc"><?= htmlspecialchars($desc) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- STATS TABLE -->
    <div class="card">
        <div class="card-header">UDP Statistics — 1.3.6.1.2.1.7.1 to .7.4</div>
        <div class="card-body" style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th style="width:180px">Object Name</th>
                        <th style="width:200px">OID</th>
                        <th style="width:100px">Value</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($udpStats as $name => [$oid, $desc]): ?>
                <tr>
                    <td style="color:#888;text-align:center"><?= $i++ ?></td>
                    <td><strong><?= htmlspecialchars($name) ?></strong></td>
                    <td class="oid"><?= htmlspecialchars($oid) ?></td>
                    <td class="val-num"><?= htmlspecialchars(snmp_val($statValues[$name])) ?></td>
                    <td style="color:#555;font-size:0.88rem"><?= htmlspecialchars($desc) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- UDP LISTENER TABLE -->
    <div class="card">
        <div class="card-header">UDP Listener Table — 1.3.6.1.2.1.7.5 (udpTable)</div>
        <div class="card-body" style="padding:0">
            <?php if (empty($udpTableData)): ?>
                <div style="padding:16px">
                    <div class="alert info">No UDP listener entries found. The agent may not have active UDP listeners.</div>
                </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Local Address (udpLocalAddress)</th>
                        <th>Local Port (udpLocalPort)</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($udpTableData as $idx => $row): ?>
                <tr>
                    <td style="text-align:center;color:#888"><?= $idx ?></td>
                    <td class="mono"><?= htmlspecialchars($row['addr']) ?></td>
                    <td class="val-num"><?= htmlspecialchars($row['port']) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div style="padding:10px 14px;color:#888;font-size:0.82rem">
                Total: <?= count($udpTableData) ?> active UDP listener(s)
            </div>
            <?php endif; ?>
        </div>
    </div>

<?php page_footer('page1.php', 'page3.php', '← Page 1: System', 'Page 3: SNMP Group →'); ?>
</div>
