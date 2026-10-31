<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\DokuBannedIpsMapping;

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

    public function getBannedIps() : false|array
    {
        $stmt = $this->db->query("SELECT * FROM doku_banned_ips");
        $bannedIps = [];
        while ($row = $stmt->fetch()) {
            $bannedIps[] = new DokuBannedIpsMapping($row);
        }
        if(empty($bannedIps)) return false;
        return $bannedIps;
    }

    public function unBanIp(string $ip) : bool
    {
        $stmt = $this->db->prepare("DELETE FROM doku_banned_ips WHERE ban_ip = :ip");
        $stmt->bindParam(":ip", $ip);
        return $stmt->execute();
    }

    public function banIp(array $data) : bool
    {
        return $this->insertAnything($data, "doku_banned_ips");
    }
}