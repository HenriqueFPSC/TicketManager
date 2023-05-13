<?php
    include_once("../database/connection.php");
    include_once("../classes/session.php");
    include_once("../classes/ticket.php");

    $session = Session::getSession();
    $db = getDatabaseConnection();
    Ticket::createTicket($db, $_SESSION[Session::USERNAME], "Technology", new DateTime(), Ticket::P_NORMAL, $_POST['subject'], $_POST['text']);
    header('Location: ../pages/dashboard.php');
?>