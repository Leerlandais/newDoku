<?php

namespace model\Mapping;

use DateTime;
use model\Abstract\AbstractMapping;

class DokuConnectionLogsMapping extends AbstractMapping
{
    private int $log_id;
    private DateTime $log_date;
    private string $log_ip;
    private string $log_page_visited;
    private ?int $log_user;

    public function getLogId(): int
    {
        return $this->log_id;
    }

    public function setLogId(int $log_id): void
    {
        $this->log_id = $log_id;
    }

    public function getLogDate(): DateTime
    {
        return $this->log_date;
    }

    public function setLogDate(string|DateTime $log_date): void
    {
        if(is_string($log_date)) {
            $log_date = new DateTime($log_date);
        }
        $this->log_date = $log_date;
    }

    public function getLogIp(): string
    {
        return $this->log_ip;
    }

    public function setLogIp(string $log_ip): void
    {
        $this->log_ip = $log_ip;
    }

    public function getLogPageVisited(): string
    {
        return htmlspecialchars_decode($this->log_page_visited);
    }

    public function setLogPageVisited(string $log_page_visited): void
    {
        $this->log_page_visited = $log_page_visited;
    }

    public function getLogUser(): ?int
    {
        return $this->log_user;
    }

    public function setLogUser(?int $log_user): void
    {
        $this->log_user = $log_user;
    }


}