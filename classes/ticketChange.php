<?php
    declare(strict_types=1);
    
    class TicketChange {
        const DEPARTMENT = 'Department';
        const PRIORITY = 'Priority';
        const STATUS = 'Status';
        const ASSIGN = 'Assign';
        public int $id;
        public int $ticketId;
        public DateTime $date;
        public string $type;
        public ?string $oldVal;
        public ?string $newVal;

        private function __construct(int $id, int $ticketId, DateTime $date, string $type, ?string $oldVal, ?string $newVal) {
            $this->id = $id;
            $this->ticketId = $ticketId;
            $this->date = $date;
            $this->type = $type;
            $this->oldVal = $oldVal;
            $this->newVal = $newVal;
        }
        private static function fromArray(array $change) : TicketChange {
            return new TicketChange((int) $change['id'], (int) $change['ticketId'], new DateTime($change['date']), $change['type'], $change['oldVal'], $change['newVal']);
        }
        public function getFormattedDate() : string {
            return $this->date->format('H:i:s d-m-Y');
        }
        public static function getTicketChange(PDO $db, int $id) : ?TicketChange {
            $stmt = $db->prepare('SELECT * FROM TicketChange WHERE id=?');
            $stmt->execute(array($id));
            $change = $stmt->fetch();
            if ($change === false) return null;
            return TicketChange::fromArray($change);
        }

        public static function getTicketChanges(PDO $db, int $id) : array {
            $stmt = $db->prepare('SELECT * FROM TicketChange WHERE ticketId=? ORDER BY date DESC');
            $stmt->execute(array($id));
            $changes = $stmt->fetchAll();
            $changeArray = array();
            foreach ($changes as $change) {
                $changeArray[] = TicketChange::fromArray($change);
            }
            return $changeArray;
        }
    }
?>