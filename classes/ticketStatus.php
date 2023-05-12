<?php
    declare(strict_types=1);
    
    class TicketStatus {
        const UNASSIGNED = 'Unassigned';
        const IN_PROGRESS = 'In progress';
        const DONE = 'Done';
        public int $id;
        public int $ticketId;
        public ?string $agentUsername; 
        public DateTime $date;
        public string $status;

        public function __construct(int $id, int $ticketId, ?string $agentUsername, DateTime $date, string $status) {
            $this->id = $id;
            $this->ticketId = $ticketId;
            $this->agentUsername = $agentUsername;
            $this->date = $date;
            $this->status = $status;
        }
        public static function createTicketStatus(PDO $db, int $ticketId, ?string $agentUsername, DateTime $date, string $text) : void {
            $stmt = $db->prepare('INSERT INTO TicketStatus (ticketId, agentUsername, date, status) VALUES (?, ?, ?, ?)');
            $stmt->execute(array($ticketId, $agentUsername, $date->format('Y-m-d H:i:s'), $text));
        }
        
    }
?>