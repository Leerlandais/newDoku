<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\DokuCluesMapping;

class CluesManager extends AbstractManager
{
    public function getDokuClues(int $id) : array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM doku_clues WHERE clue_doku_id = :id");
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $clues = [];
        while ($clue = $stmt->fetch()) {
            $clues[] = new DokuCluesMapping($clue);
        }
        if(empty($clues)) return false;
        return $clues;
    }
}