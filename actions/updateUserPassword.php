<?php
    require_once(dirname(__DIR__) . "/classes/user.php");
    require_once(dirname(__DIR__) . "/classes/session.php");
    require_once(dirname(__DIR__) . "/database/connection.php");
    $session = Session::getSession();
    if (!isset($_POST['csrf']) || $session->getCSRF() !== $_POST['csrf']) {
        $session->addToast(Session::ERROR, 'Request isn\'t legitimate.');
        die(header('Location: ../index.php?page=dashboard'));
    }

    $session->saveInput(Session::U_CURR_PASSWORD , $_POST['curr-password']);
    $session->saveInput(Session::U_PASSWORD1     , $_POST['password1']);
    $session->saveInput(Session::U_PASSWORD2     , $_POST['password2']);

    $db = getDatabaseConnection();
    
    if (!User::passwordMatchesUser($db, $_SESSION[Session::USERNAME], $_POST['curr-password'])) {
        $session->addToast(Session::ERROR, "Your current password is wrong.");
        die(header('Location: ../index.php?page=settings'));
    }
    
    if ($_POST['password1'] !== $_POST['password2']) {
        $session->addToast(Session::ERROR, "Passwords don't match.");
        die(header('Location: ../index.php?page=settings'));
    }

    $validation = User::validateUpdatePassword($db, $_SESSION[Session::USERNAME], $_POST['password1']);
    if ($validation !== null) { // Error
        $session->addToast(Session::ERROR, $validation);
        die(header('Location: ../index.php?page=settings'));
    }

    User::updateUserPassword($db, $_SESSION[Session::USERNAME], $_POST['password1']);

    $session->clearInput();
    $session->addToast(Session::SUCCESS, 'Password changed successfully!');
    die(header('Location: ../index.php?page=settings'));
?>