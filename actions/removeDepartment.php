<?php
    require_once(dirname(__DIR__) . "/database/connection.php");
    require_once(dirname(__DIR__) . "/classes/session.php");
    require_once(dirname(__DIR__) . "/classes/department.php");
    $session = Session::getSession();
    if (!isset($_POST['csrf']) || $session->getCSRF() !== $_POST['csrf']) {
        $session->addToast(Session::ERROR, 'Request isn\'t legitimate.');
        die(header('Location: ../index.php?page=dashboard'));
    }

    if (!isset($_POST['department'])) {
        $session->addToast(Session::ERROR, 'Missing department.');
        die(header('Location: ../index.php?page=departments'));
    }
    
    $db = getDatabaseConnection();
    Department::removeDepartment($db, $_POST['department']);
    $session->addToast(Session::SUCCESS, 'Department Removed Successfully!');
    die(header('Location: ../index.php?page=departments'));
?>