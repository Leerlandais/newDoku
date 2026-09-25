<?php

namespace model\Mapping;

use model\Abstract\AbstractMapping;

class DokuCluesMapping extends AbstractMapping
{
    private int $clue_id;
    private int $clue_doku_id;
    private int $clue_level;
    private string $clue_header;
    private string $clue_text;

    public function getClueId(): int
    {
        return $this->clue_id;
    }

    public function setClueId(int $clue_id): void
    {
        $this->clue_id = $clue_id;
    }

    public function getClueDokuId(): int
    {
        return $this->clue_doku_id;
    }

    public function setClueDokuId(int $clue_doku_id): void
    {
        $this->clue_doku_id = $clue_doku_id;
    }

    public function getClueLevel(): int
    {
        return $this->clue_level;
    }

    public function setClueLevel(int $clue_level): void
    {
        $this->clue_level = $clue_level;
    }

    public function getClueHeader(): string
    {
        return htmlspecialchars_decode($this->clue_header);
    }

    public function setClueHeader(string $clue_header): void
    {
        $this->clue_header = $clue_header;
    }


    public function getClueText(): string
    {
        return htmlspecialchars_decode($this->clue_text);
    }

    public function setClueText(string $clue_text): void
    {
        $this->clue_text = $clue_text;
    }

}