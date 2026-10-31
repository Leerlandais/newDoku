<?php

namespace model\Mapping;

use model\Abstract\AbstractMapping;

class DokuBannedIpsMapping extends AbstractMapping
{
    private int $ban_id;
    private string $ban_ip;

    public function getBanId(): int
    {
        return $this->ban_id;
    }

    public function setBanId(int $ban_id): void
    {
        $this->ban_id = $ban_id;
    }

    public function getBanIp(): string
    {
        return $this->ban_ip;
    }

    public function setBanIp(string $ban_ip): void
    {
        $this->ban_ip = $ban_ip;
    }


}