<?php
require_once 'shared_header.php';

$ip = $SNMP_IP;
$ro = $SNMP_COMMUNITY_RO;
$rw = $SNMP_COMMUNITY_RW;

$feedback = [];

// Handle SET
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save') {
    $toSet = [
        'sysContact'  => ['.1.3.6.1.2.1.1.4.0', $_POST['sysContact']  ?? ''],
        'sysName'     => ['.1.3.6.1.2.1.1.5.0', $_POST['sysName']     ?? ''],
        'sysLocation' => ['.1.3.6.1.2.1.1.6.0', $_POST['sysLocation'] ?? ''],
    ];
    foreach ($toSet as $label => [$oid, $val]) {
        try {
            $r = @snmp2_set($ip, $rw, $oid, 's', $val, 1000000, 1);
            $feedback[] = ['type'=>'success', 'msg'=> "$label updated successfully."];
        } catch (Exception $e) {
            $feedback[] = ['type'=>'error', 'msg'=> "Failed to update $label."];
        }
    }
}

// Read all system values
$data = [
    'sysDescr'    => ['.1.3.6.1.2.1.1.1.0', 'System Description',  false],
    'sysObjectID' => ['.1.3.6.1.2.1.1.2.0', 'Object ID',           false],
    'sysUpTime'   => ['.1.3.6.1.2.1.1.3.0', 'Up Time',             false],
    'sysContact'  => ['.1.3.6.1.2.1.1.4.0', 'Contact',             true],
    'sysName'     => ['.1.3.6.1.2.1.1.5.0', 'System Name',         true],
    'sysLocation' => ['.1.3.6.1.2.1.1.6.0', 'Location',            true],
    'sysServices' => ['.1.3.6.1.2.1.1.7.0', 'Services',            false],
];

$values = [];
foreach ($data as $name => [$oid]) {
    $values[$name] = snmp_get_safe($ip, $ro, $oid);
}

page_header('Page 1 — System Group', 'page1');
?>

<div class="wrapper">
    <div class="page-title">
        <h1>Page 1 — System Group</h1>
        <p>OID: 1.3.6.1.2.1.1 &nbsp;|&nbsp; Agent: <?= htmlspecialchars($ip) ?> &nbsp;|&nbsp; Community: <?= htmlspecialchars($ro) ?></p>
    </div>

    <?php foreach ($feedback as $fb): ?>
        <div class="alert <?= $fb['type'] ?>"><?= htmlspecialchars($fb['msg']) ?></div>
    <?php endforeach; ?>

    <!-- READ ONLY TABLE -->
    <div class="card">
        <div class="card-header">System Information (Read Only)</div>
        <div class="card-body" style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th style="width:160px">Object</th>
                        <th style="width:180px">OID</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $name => [$oid, $label, $editable]): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($name) ?></strong><br><small style="color:#888"><?= htmlspecialchars($label) ?></small></td>
                    <td class="oid"><?= htmlspecialchars($oid) ?></td>
                    <td class="val"><?= htmlspecialchars(snmp_val($values[$name])) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- EDIT FORM -->
    <div class="card">
        <div class="card-header">✎ Edit System Fields (Community: <?= htmlspecialchars($rw) ?> — Read/Write)</div>
        <div class="card-body">
            <form method="POST" action="page1.php">
                <input type="hidden" name="action" value="save">

                <div class="form-group">
                    <label for="sysContact">sysContact<br><span class="oid">.1.3.6.1.2.1.1.4.0</span></label>
                    <input type="text" id="sysContact" name="sysContact"
                           value="<?= htmlspecialchars(snmp_val($values['sysContact'])) ?>"
                           placeholder="Contact person or email">
                </div>

                <div class="form-group">
                    <label for="sysName">sysName<br><span class="oid">.1.3.6.1.2.1.1.5.0</span></label>
                    <input type="text" id="sysName" name="sysName"
                           value="<?= htmlspecialchars(snmp_val($values['sysName'])) ?>"
                           placeholder="System hostname">
                </div>

                <div class="form-group">
                    <label for="sysLocation">sysLocation<br><span class="oid">.1.3.6.1.2.1.1.6.0</span></label>
                    <input type="text" id="sysLocation" name="sysLocation"
                           value="<?= htmlspecialchars(snmp_val($values['sysLocation'])) ?>"
                           placeholder="Physical location">
                </div>

                <button type="submit" class="btn">💾 Save All Changes</button>
            </form>
        </div>
    </div>

<?php page_footer('', 'page2.php', '← Previous', 'Page 2: UDP Group →'); ?>
</div>
