<?php require_once 'shared_header.php'; page_header('SNMP Manager', 'home'); ?>

<div class="wrapper">
    <div class="page-title">
        <h1>SNMP Network Manager</h1>
        <p>Computer Networks 2 - Assignment 2 &nbsp;|&nbsp; An-Najah National University &nbsp;|&nbsp;</p>
    </div>

    <!-- Team Members Row -->
    <div class="stat-row page-title">
        <div class="stat-box" style="text-align:center;">
            <h1>Yousef Yaser Hajeer</h1>
            <h1>12217097</h1>
        </div>
        <div class="stat-box" style="text-align:center;">
            <h1>Mohammed Nihad Bishawi</h1>
            <h1>12216904</h1>
        </div>
    </div>

    <div class="stat-row">
        <a href="page1.php" style="text-decoration:none">
            <div class="stat-box">
                <div class="num">01</div>
                <div class="lbl">System Group</div>
                <div class="desc">sysContact, sysName, sysLocation<br>Read &amp; Write</div>
            </div>
        </a>
        <a href="page2.php" style="text-decoration:none">
            <div class="stat-box">
                <div class="num">02</div>
                <div class="lbl">UDP Group</div>
                <div class="desc">UDP Statistics &amp; Listener Table</div>
            </div>
        </a>
        <a href="page3.php" style="text-decoration:none">
            <div class="stat-box">
                <div class="num">03</div>
                <div class="lbl">SNMP Group</div>
                <div class="desc">Statistics via GET loop &amp; WALK</div>
            </div>
        </a>
    </div>

    <div class="card">
        <div class="card-header">Agent Information</div>
        <div class="card-body">
            <table>
                <tbody>
                    <tr><td style="width:180px;font-weight:bold">Agent IP</td><td class="val">127.0.0.1</td></tr>
                    <tr><td style="font-weight:bold">Port</td><td class="val">UDP 161</td></tr>
                    <tr><td style="font-weight:bold">Read Community</td><td class="val">public</td></tr>
                    <tr><td style="font-weight:bold">Write Community</td><td class="val">private</td></tr>
                    <tr><td style="font-weight:bold">Protocol</td><td class="val">SNMPv2c</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="footer">
    An-Najah National University &nbsp;·&nbsp; Computer Networks 2 &nbsp;·&nbsp; Spring 2026
</div>
</body></html>