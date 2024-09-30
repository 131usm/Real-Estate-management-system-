<?php

$user = Auth::user();
match ($user['role']) {
    'ADMIN' => include 'admin/testimonial.php',
    'LAND LORD' => include 'land_lord/testimonial.php',
    'TENANT' => include 'tenant/testimonial.php',
};