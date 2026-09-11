<?php

namespace model\Mapping;

use DateTime;
use model\Abstract\AbstractMapping;

class DokuDokusMapping extends AbstractMapping
{
    private int $doku_id;
    private string $doku_title;
    private string $doku_url;
    private int $doku_setter_id;
    private string $doku_setter_name;

    private DateTime $doku_date;
    private ?int $doku_duration;

    public function getDokuId(): int
    {
        return $this->doku_id;
    }

    public function setDokuId(int $doku_id): void
    {
        $this->doku_id = $doku_id;
    }

    public function getDokuTitle(): string
    {
        return $this->doku_title;
    }

    public function setDokuTitle(string $doku_title): void
    {
        $this->doku_title = $doku_title;
    }

    public function getDokuUrl(): string
    {
        return $this->doku_url;
    }

    public function setDokuUrl(string $doku_url): void
    {
        $this->doku_url = $doku_url;
    }

    public function getDokuSetterId(): int
    {
        return $this->doku_setter_id;
    }

    public function setDokuSetterId(int $doku_setter_id): void
    {
        $this->doku_setter_id = $doku_setter_id;
    }

    public function getDokuSetterName(): string
    {
        return htmlspecialchars_decode($this->doku_setter_name);
    }

    public function setDokuSetterName(string $doku_setter_name): void
    {
        $this->doku_setter_name = $doku_setter_name;
    }

    public function getDokuDate(): DateTime
    {
        return $this->doku_date;
    }

    public function setDokuDate(DateTime|string $doku_date): void
    {
        if(is_string($doku_date)) {
            $doku_date = new DateTime($doku_date);
        }
        $this->doku_date = $doku_date;
    }

    public function getDokuDuration(): ?int
    {
        return $this->doku_duration;
    }

    public function setDokuDuration(?int $doku_duration): void
    {
        $this->doku_duration = $doku_duration;
    }



}