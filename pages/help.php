<?php
    // Templates
    require_once(dirname(__DIR__) . "/templates/header.php");
    require_once(dirname(__DIR__) . "/templates/footer.php");
    require_once(dirname(__DIR__) . "/templates/sidebar.php");
    require_once(dirname(__DIR__) . "/templates/faq.php");
    require_once(dirname(__DIR__) . "/templates/toast.php");
    // Database
    require_once(dirname(__DIR__) . "/database/connection.php");
    // Classes
    require_once(dirname(__DIR__) . "/classes/session.php");
    require_once(dirname(__DIR__) . "/classes/faq.php");
    // Session
    $session = Session::getSession();

    if (!$session->isLoggedIn()) {
        $session->addToast(Session::ERROR, 'You are not logged in!');
        die(header('Location: ./index.php?page=login'));
    }

    function drawPage(array $getArray) {
        global $session;

        $db = getDatabaseConnection();
        $faqs = FAQ::getAll($db);
        
        // Draw Page
        drawHeader($session);
        drawSidebar($session, 'help');
?>
<main class="main-sidebar">
    <div id="faq" class="page center-toast">
        <h1 id="faq-title" class="title">Frequently Asked Questions (FAQ)</h1>
        <?php
            foreach ($faqs as $faq) drawFAQ($faq);
            if ($session->getMyRights(User::USERTYPE_ADMIN)) { ?>
                <div class="big-button"><button id="faq-add-button">Add FAQ</button></div>
                <div class="big-button"><button id="faq-remove-button" class="red">Remove FAQ</button></div>
        <?php } 
        drawToasts($session);
        ?>
    </div>
    <div id='popup'></div>
</main>
<?php  
        drawFooter();
    }
?>