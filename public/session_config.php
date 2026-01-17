<?php

ini_set('session.gc_maxlifetime', 600); // 10 minutes
ini_set('session.cookie_lifetime', 600);

session_start();

$_SESSION['user'] = 'Meal Planner User';
$_SESSION['created_at'] = time();

echo "Session ID: " . session_id() . "<br>";
echo "Session Lifetime (seconds): " . ini_get('session.gc_maxlifetime') . "<br>";
echo "Session Created At: " . date('H:i:s', $_SESSION['created_at']);
