<?php
    require_once __DIR__ . '/site/php/bootstrap-application.php';
    $action = (isset($_GET['action']) && $_GET['action'] ? 'actions/' . $_GET['action'] : 'pages/index');
    
    require __DIR__ . '/site/php/' . $action . '.php';
