<?php

namespace model\Manager;

use model\Abstract\AbstractManager;

class SecurityManager extends AbstractManager
{
    public function checkForBanned(string $ip) : bool
    {

        $stmt = $this->db->prepare("SELECT * FROM doku_banned_ips WHERE ban_ip = :ip");
        $stmt->bindParam(":ip", $ip);
        $stmt->execute();
        $result = $stmt->fetch();
        return (bool)$result;
    }
}