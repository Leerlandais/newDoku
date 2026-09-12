<?php

namespace model\Mapping;

use model\Abstract\AbstractMapping;

class DokuSettersMapping extends AbstractMapping
{
    private int $setter_id;
    private string $setter_name;
    private int $setter_count;

    public function getSetterId(): int
    {
        return $this->setter_id;
    }

    public function setSetterId(int $setter_id): void
    {
        $this->setter_id = $setter_id;
    }

    public function getSetterName(): string
    {
        return htmlspecialchars_decode($this->setter_name);
    }

    public function setSetterName(string $setter_name): void
    {
        $this->setter_name = $setter_name;
    }

    public function getSetterCount(): int
    {
        return $this->setter_count;
    }

    public function setSetterCount(int $setter_count): void
    {
        $this->setter_count = $setter_count;
    }


}