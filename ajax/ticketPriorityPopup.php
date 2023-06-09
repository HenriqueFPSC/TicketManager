<?php
    require_once(dirname(__DIR__) . "/templates/priority.php");
    require_once(dirname(__DIR__) . "/database/connection.php");
    require_once(dirname(__DIR__) . "/classes/user.php");
    require_once(dirname(__DIR__) . "/classes/ticket.php");
    require_once(dirname(__DIR__) . "/classes/session.php");
    $session = Session::getSession();
    
    $db = getDatabaseConnection();
?>
<div id="popup-darken" onclick="closePopup()"></div>
<div id="popup-form">
    <div class="popup-item">
        <span> Ticket ID: <?=htmlentities($_POST['id'])?></span>
    </div>
    <form action="../actions/changeTicketPriority.php" method="post">
        <?php foreach (array(Ticket::NORMAL, Ticket::HIGH, Ticket::URGENT) as $priority) {?>
            <div class="popup-item">
                <input class="priority-item" type="radio" name="priority" value="<?=$priority?>" <?php if ($_POST['priority'] === $priority) { ?> checked <?php } ?>>
                <?php drawPriority($priority); ?>
            </div>
        <?php } ?>
        <div class="popup-item">
            <button type="submit" class="submit-button">Change</button>
        </div>
        <input type="hidden" name='id' value="<?=htmlentities($_POST['id'])?>"> 
        <input type="hidden" name="csrf" value="<?=$session->getCSRF()?>">
    </form>
    <button type="button" class="red" onclick="closePopup()">Close</button>
</div>