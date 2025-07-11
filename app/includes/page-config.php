<?php
function setPageConfig($config) {
    global $pageTitle, $includeAnimations, $includeAuthScript, $includeRoomsScript, $includeUtilsScript, $includeAppScript;
    
    $pageTitle = $config['title'] ?? 'Sistema de Reservas';
    $includeAnimations = $config['animations'] ?? false;
    $includeAuthScript = $config['authScript'] ?? false;
    $includeRoomsScript = $config['roomsScript'] ?? false;
    $includeUtilsScript = $config['utilsScript'] ?? true;
    $includeAppScript = $config['appScript'] ?? true;
}

function getLoginConfig() {
    return [
        'title' => 'Login | Sistema de Reservas',
        'animations' => true,
        'authScript' => true,
        'roomsScript' => false,
        'utilsScript' => true,
        'appScript' => true
    ];
}

function getDashboardConfig() {
    return [
        'title' => 'Dashboard | Sistema de Reservas',
        'animations' => false,
        'authScript' => false,
        'roomsScript' => true,
        'utilsScript' => true,
        'appScript' => true
    ];
}
?> 