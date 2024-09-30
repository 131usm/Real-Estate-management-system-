<?php

$user = Auth::user();
match ($user['role']) {
    'ADMIN' => include 'admin/dashboard.php',
    'LAND LORD' => include 'land_lord/dashboard.php',
    'TENANT' => include 'tenant/dashboard.php',
};