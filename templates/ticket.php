<?php
    require_once(dirname(__DIR__) . "/classes/ticket.php");
    require_once(dirname(__DIR__) . "/templates/profile.php");
    require_once(dirname(__DIR__) . "/templates/priority.php");
    require_once(dirname(__DIR__) . "/templates/status.php");
    function _drawTicket(Ticket $ticket) {
?>
<tr class="ticket">
    <td class="ticket-username"><?php drawProfile($ticket->publisher, true); ?></td>
    <td class="ticket-subject"><?=htmlentities($ticket->subject)?></td>
    <td class="ticket-department"><?=htmlentities($ticket->department)?></td>
    <td class="ticket-priority"><?php drawPriority($ticket->status->priority); ?></td>
    <td class="ticket-status"><?php drawStatus($ticket->status->status, $ticket->status->agentUsername); ?></td>
    <td class="ticket-edit">
        <form class="ticket-page-form" action="./index.php" method="get">
            <input type="hidden" name="page" value="ticket">
            <input type="hidden" name="id" value="<?=$ticket->id?>">
            <button type='submit' class="ticket-page-submit">See Ticket</button>
        </form>
    </td>
</tr>
<?php
    }

    function drawTickets(array $tickets) {
?>
<table id="tickets">
    <thead>
        <tr>
            <th style="width: 0;">User</th>
            <th>Ticket</th>
            <th style="width: 0;">Department</th>
            <th style="width: 0;">Priority</th>
            <th style="width: 0;">Status</th>
            <th style="width: 0;">See Ticket</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($tickets as $ticket) {
        _drawTicket($ticket);
    }
    ?>
    </tbody>
</table>
<?php
    }
    function _drawTicketDepartment(Ticket $ticket) {
        ?>
        <tr class="ticket">
            <td class="ticket-username"><?php drawProfile($ticket->publisher, true); ?></td>
            <td class="ticket-subject"><?=htmlentities($ticket->subject)?></td>
            <td class="ticket-priority"><?php drawPriority($ticket->status->priority); ?></td>
            <td class="ticket-status"><?php drawStatus($ticket->status->status, $ticket->status->agentUsername); ?></td>
            <td class="ticket-edit">
                <form class="ticket-form" action="./index.php" method="get">
                    <input type="hidden" name="page" value="ticket">
                    <input type="hidden" name="id" value="<?=$ticket->id?>">
                    <button type='submit' class="ticket-submit">See Ticket</button>
                </form>
            </td>
        </tr>
        <?php
            }
    function drawTicketsDepartment(array $tickets, string $department) {
        ?>
        <h1> <?=htmlentities($department); ?></h1>
        <table id="tickets">
            <thead>
                <tr>
                    <th style="width: 0;">User</th>
                    <th>Ticket</th>
                    <th style="width: 0;">Priority</th>
                    <th style="width: 0;">Status</th>
                    <th style="width: 0;">See Ticket</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($tickets as $ticket) {
                _drawTicketDepartment($ticket);
            }
            ?>
            </tbody>
        </table>
        <?php
            }
?>