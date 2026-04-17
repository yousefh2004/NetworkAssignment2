<!DOCTYPE html>
<html>
<head>
    <title>SNMP Network Manager Yousef & Mohammad </title>
     <link rel="stylesheet" href="css/index.css">
</head>
<body>
<div>
    <h1>SNMP Network Manager</h1>
    <p>Computer Networks 2 - Assignment 2 - An-Najah National University - Spring 2026</p>

    <div>
        <div>
            <h2>Yousef Yaser Hajeer</h2>
            <p>12217097</p>
        </div>
        <div>
            <h2>Mohammed Nihad Bishawi</h2>
            <p>12216904</p>
        </div>
    </div>

    <div>
        <a href="./php/page1.php">
            <div>
                <h2>01</h2>
                <p>System Group</p>
            </div>
        </a>

        <a href="page2.php">
            <div>
                <h2>02</h2>
                <p>UDP Group</p>
            </div>
        </a>

        <a href="page3.php">
            <div>
                <h2>03</h2>
                <p>SNMP Group</p>
            </div>
        </a>
    </div>

    <h2>Agent Information</h2>
    <table border="1" cellpadding="8">
        <tr>
            <td><b>Agent IP</b></td>
            <td><?php
            preg_match('/IPv4 Address[^\:]*:\s*([\d\.]+)/', shell_exec('ipconfig'), $m);
            $ip = $m[1];
            echo $ip; ?></td>
        </tr>
        <tr>
            <td><b>Port</b></td>
            <td>UDP 161</td>
        </tr>
        <tr>
            <td><b>Protocol</b></td>
            <td>SNMPv2</td>
        </tr>
    </table>
</div>
</body>
</html>