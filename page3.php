<?php
require_once 'shared_header.php';

$ip = $SNMP_IP;
$ro = $SNMP_COMMUNITY_RO;

// Object name + description map (OIDs 1..30 except 7 and 23)
$snmpObjects = [
     1 => ['snmpInPkts',              'Total messages delivered to SNMP from transport'],
     2 => ['snmpOutPkts',             'Total SNMP messages passed to transport service'],
     3 => ['snmpInBadVersions',       'Messages with unsupported SNMP version'],
     4 => ['snmpInBadCommunityNames', 'Messages with unknown community name'],
     5 => ['snmpInBadCommunityUses',  'Messages with valid community but improper operation'],
     6 => ['snmpInASNParseErrs',      'Messages with ASN.1 or BER errors'],
     8 => ['snmpInTooBigs',           'PDUs with tooBig error status'],
     9 => ['snmpInNoSuchNames',       'PDUs with noSuchName error status'],
    10 => ['snmpInBadValues',         'PDUs with badValue error status'],
    11 => ['snmpInReadOnlys',         'PDUs with readOnly error status'],
    12 => ['snmpInGenErrs',           'PDUs with genErr error status'],
    13 => ['snmpInTotalReqVars',      'MIB objects retrieved successfully'],
    14 => ['snmpInTotalSetVars',      'MIB objects altered successfully'],
    15 => ['snmpInGetRequests',       'GetRequest PDUs accepted and processed'],
    16 => ['snmpInGetNexts',          'GetNextRequest PDUs accepted and processed'],
    17 => ['snmpInSetRequests',       'SetRequest PDUs accepted and processed'],
    18 => ['snmpInGetResponses',      'GetResponse PDUs accepted and processed'],
    19 => ['snmpInTraps',             'Trap PDUs accepted and processed'],
    20 => ['snmpOutTooBigs',          'GetResponse PDUs with tooBig error generated'],
    21 => ['snmpOutNoSuchNames',      'GetResponse PDUs with noSuchName error generated'],
    22 => ['snmpOutBadValues',        'GetResponse PDUs with badValue error generated'],
    24 => ['snmpOutGenErrs',          'GetResponse PDUs with genErr error generated'],
    25 => ['snmpOutGetRequests',      'GetRequest PDUs generated'],
    26 => ['snmpOutGetNexts',         'GetNextRequest PDUs generated'],
    27 => ['snmpOutSetRequests',      'SetRequest PDUs generated'],
    28 => ['snmpOutGetResponses',     'GetResponse PDUs generated'],
    29 => ['snmpOutTraps',            'Trap PDUs generated'],
    30 => ['snmpEnableAuthenTraps',   'Authentication failure traps (1=enabled, 2=disabled)'],
];

$base = '.1.3.6.1.2.1.11';

// METHOD 1 — snmp2_get() loop
$method1 = [];
foreach ($snmpObjects as $x => $info) {
    $method1[$x] = snmp_get_safe($ip, $ro, "$base.$x.0");
}

// METHOD 2 — snmp2_walk()
$method2raw  = array_values(snmp_walk_safe($ip, $ro, $base));
$method2names = array_values(array_map(fn($v) => $v[0], $snmpObjects));

page_header('Page 3 — SNMP Group', 'page3');
?>

<div class="wrapper">
    <div class="page-title">
        <h1>Page 3 — SNMP Group Statistics</h1>
        <p>OID: 1.3.6.1.2.1.11 &nbsp;|&nbsp; Agent: <?= htmlspecialchars($ip) ?> &nbsp;|&nbsp; OIDs x = 1–30 (excluding 7 and 23)</p>
    </div>

    <!-- SUMMARY BOXES -->
    <div class="stat-row">
        <?php
        $highlights = [
            ['Pkts In',       $method1[1]  ?? 'N/A', '#1a6a3a'],
            ['Pkts Out',      $method1[2]  ?? 'N/A', '#1a3a6a'],
            ['GET Requests',  $method1[15] ?? 'N/A', '#1a5a6a'],
            ['GET Responses', $method1[28] ?? 'N/A', '#4a1a6a'],
            ['Traps In',      $method1[19] ?? 'N/A', '#8a5000'],
            ['Traps Out',     $method1[29] ?? 'N/A', '#8a1a1a'],
        ];
        foreach ($highlights as [$lbl, $raw, $col]):
            $val = snmp_val($raw);
        ?>
        <div class="stat-box" style="border-top-color:<?= $col ?>">
            <div class="num" style="color:<?= $col ?>"><?= htmlspecialchars($val) ?></div>
            <div class="lbl"><?= htmlspecialchars($lbl) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- SIDE BY SIDE TABLES -->
    <div class="two-col">

        <!-- METHOD 1 -->
        <div class="card">
            <div class="card-header">Method 1 — snmp2_get() Loop</div>
            <div style="padding:8px 14px;background:#f7f9fc;font-size:0.8rem;color:#555;border-bottom:1px solid #e2e8f0;font-family:monospace">
                snmp2_get($ip, "public", "1.3.6.1.2.1.11.<b>x</b>.0")
            </div>
            <div style="padding:0">
                <table>
                    <thead>
                        <tr>
                            <th style="width:36px">ID</th>
                            <th>Name</th>
                            <th style="width:110px">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($snmpObjects as $x => [$name]): ?>
                    <tr>
                        <td style="text-align:center;color:#888;font-size:0.85rem"><?= $x ?></td>
                        <td style="font-size:0.85rem"><?= htmlspecialchars($name) ?></td>
                        <td class="val-num" style="text-align:center"><?= htmlspecialchars(snmp_val($method1[$x])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- METHOD 2 -->
        <div class="card">
            <div class="card-header">Method 2 — snmp2_walk()</div>
            <div style="padding:8px 14px;background:#f7f9fc;font-size:0.8rem;color:#555;border-bottom:1px solid #e2e8f0;font-family:monospace">
                snmp2_walk($ip, "public", "1.3.6.1.2.1.11")
            </div>
            <div style="padding:0">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px">Item #</th>
                            <th>Name</th>
                            <th style="width:110px">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($method2raw)): ?>
                        <tr><td colspan="3" style="text-align:center;padding:20px;color:#888">No data returned by walk.</td></tr>
                    <?php else: ?>
                    <?php foreach ($method2raw as $i => $raw): ?>
                    <tr>
                        <td style="text-align:center;color:#888;font-size:0.85rem"><?= $i ?></td>
                        <td style="font-size:0.85rem"><?= htmlspecialchars($method2names[$i] ?? "object_$i") ?></td>
                        <td class="val-num" style="text-align:center;color:#1a3a6a"><?= htmlspecialchars(snmp_val($raw)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
                <?php if (!empty($method2raw)): ?>
                <div style="padding:8px 14px;color:#888;font-size:0.8rem;border-top:1px solid #e2e8f0">
                    <?= count($method2raw) ?> objects returned by walk
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /two-col -->

    <!-- FULL DETAIL TABLE -->
    <div class="card">
        <div class="card-header">Full Detail — All SNMP Objects with Descriptions</div>
        <div style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th style="width:200px">Object Name</th>
                        <th style="width:180px">OID</th>
                        <th style="width:100px">Value</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($snmpObjects as $x => [$name, $desc]): ?>
                <tr>
                    <td style="text-align:center;color:#888"><?= $x ?></td>
                    <td><strong style="font-size:0.88rem"><?= htmlspecialchars($name) ?></strong></td>
                    <td class="oid"><?= htmlspecialchars("$base.$x.0") ?></td>
                    <td class="val-num" style="text-align:center"><?= htmlspecialchars(snmp_val($method1[$x])) ?></td>
                    <td style="color:#555;font-size:0.83rem"><?= htmlspecialchars($desc) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php page_footer('page2.php', '', '← Page 2: UDP Group', ''); ?>
</div>
