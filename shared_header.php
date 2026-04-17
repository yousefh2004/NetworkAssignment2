<?php
$SNMP_IP           = "127.0.0.1";
$SNMP_COMMUNITY_RO = "public";
$SNMP_COMMUNITY_RW = "private";

function page_header(string $title, string $activePage = ''): void { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — SNMP Manager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            color: #222;
            font-size: 15px;
        }

      
        .navbar {
            background: #1a3a6a;
            padding: 0 24px;
            display: flex;
            align-items: center;
            height: 52px;
            gap: 8px;
        }
        .navbar .brand {
            color: #fff;
            font-weight: bold;
            font-size: 1.1rem;
            margin-right: 20px;
            text-decoration: none;
        }
        .navbar a {
            color: #aac4f0;
            text-decoration: none;
            padding: 6px 16px;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background 0.15s;
        }
        .navbar a:hover { background: #2a4f8a; color: #fff; }
        .navbar a.active { background: #fff; color: #1a3a6a; font-weight: bold; }

        /* WRAPPER */
        .wrapper {
            max-width: 1100px;
            margin: 28px auto;
            padding: 0 20px 60px;
        }

        /* PAGE TITLE */
        .page-title {
            background: #fff;
            border-left: 5px solid #1a3a6a;
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 0 6px 6px 0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .page-title h1 { font-size: 1.4rem; color: #1a3a6a; }
        .page-title p  { color: #666; font-size: 0.88rem; margin-top: 4px; }

        /* CARD */
        .card {
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-header {
            background: #1a3a6a;
            color: #fff;
            padding: 10px 18px;
            font-size: 0.95rem;
            font-weight: bold;
        }
        .card-body { padding: 20px; }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
        }
        thead th {
            background: #1a3a6a;
            color: #fff;
            padding: 10px 14px;
            text-align: left;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        tbody tr:nth-child(even) { background: #f7f9fc; }
        tbody tr:hover { background: #eaf0fb; }
        tbody td {
            padding: 9px 14px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        
        .val {
            font-weight: bold;
            color: #1a3a6a;
            font-size: 1rem;
        }
        .val-num {
            font-weight: bold;
            color: #c0392b;
            font-size: 1.05rem;
        }
        .oid  { color: #888; font-size: 0.8rem; font-family: monospace; }
        .mono { font-family: monospace; }

        /* STAT CARDS ROW */
        .stat-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .stat-box {
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            padding: 18px 16px;
            border-top: 4px solid #1a3a6a;
            text-align: center;
        }
        .stat-box .num {
            font-size: 2.2rem;
            font-weight: bold;
            color: #c0392b;
            line-height: 1;
            margin-bottom: 6px;
        }
        .stat-box .lbl {
            font-size: 0.82rem;
            color: #555;
            font-weight: bold;
        }
        .stat-box .desc {
            font-size: 0.75rem;
            color: #999;
            margin-top: 4px;
        }

        /* FORM */
        .form-group {
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 10px 12px;
            align-items: center;
            margin-bottom: 12px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
            font-size: 0.9rem;
        }
        .form-group input[type=text] {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 0.92rem;
            width: 100%;
        }
        .form-group input[type=text]:focus {
            outline: none;
            border-color: #1a3a6a;
            box-shadow: 0 0 0 2px rgba(26,58,106,0.15);
        }
        .btn {
            background: #1a3a6a;
            color: #fff;
            border: none;
            padding: 9px 24px;
            border-radius: 4px;
            font-size: 0.92rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 8px;
        }
        .btn:hover { background: #2a5298; }

        .alert {
            padding: 11px 16px;
            border-radius: 4px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
        .alert.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert.info    { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .bottom-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 32px;
            padding-top: 16px;
            border-top: 2px solid #dde3ed;
        }
        .bottom-nav a {
            background: #1a3a6a;
            color: #fff;
            text-decoration: none;
            padding: 8px 22px;
            border-radius: 4px;
            font-size: 0.88rem;
            font-weight: bold;
        }
        .bottom-nav a:hover { background: #2a5298; }
        .bottom-nav .disabled {
            background: #ccc;
            color: #fff;
            padding: 8px 22px;
            border-radius: 4px;
            font-size: 0.88rem;
            font-weight: bold;
        }

   
        .footer {
            text-align: center;
            color: #888;
            font-size: 0.8rem;
            padding: 16px;
            border-top: 1px solid #dde3ed;
            margin-top: 20px;
        }
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 700px) { .two-col { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="navbar">
    <a class="brand" href="index.php">SNMP Manager</a>
    <a href="index.php"    class="<?= $activePage==='home'  ?'active':'' ?>">Home</a>
    <a href="page1.php"    class="<?= $activePage==='page1' ?'active':'' ?>">System</a>
    <a href="page2.php"    class="<?= $activePage==='page2' ?'active':'' ?>">UDP</a>
    <a href="page3.php"    class="<?= $activePage==='page3' ?'active':'' ?>">SNMP</a>
</div>

<?php } // end page_header

function page_footer(string $prev='', string $next='', string $prevLbl='← Previous', string $nextLbl='Next →'): void { ?>

<div class="footer">
    An-Najah National University &nbsp;·&nbsp; Computer Networks 2 &nbsp;·&nbsp; Spring 2026
</div>

</body>
</html>
<?php } 

function snmp_val(string $raw): string {
    if (preg_match('/^[A-Za-z0-9 _\-]+:\s*"?(.+?)"?\s*$/', trim($raw), $m)) {
        return $m[1];
    }
    return trim($raw);
}

function snmp_get_safe(string $ip, string $community, string $oid): string {
    try {
        $r = @snmp2_get($ip, $community, $oid, 1000000, 1);
        return ($r !== false) ? $r : 'N/A';
    } catch (Exception $e) { return 'N/A'; }
}

function snmp_walk_safe(string $ip, string $community, string $oid): array {
    try {
        $r = @snmp2_walk($ip, $community, $oid, 1000000, 1);
        return ($r !== false) ? $r : [];
    } catch (Exception $e) { return []; }
}
