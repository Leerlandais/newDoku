<?php

namespace model\Manager;

use model\Abstract\AbstractManager;

class AdminManager extends AbstractManager
{
    public function addClue(array $data) : bool
    {
        return $this->insertAnything($data, "doku_clues");
    }
}