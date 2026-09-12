<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\DokuDokusMapping;

class DokuManager extends AbstractManager
{
    public function getLatestPuzzles() : array|false
    {
        $date = date("Y-m-d");
        $query = $this->db->query("SELECT 
    dd.*,
    ds.setter_name AS doku_setter_name,
    GROUP_CONCAT(dv.variant_name ORDER BY dv.variant_name SEPARATOR ', ') AS doku_variant_names
FROM doku_dokus dd
LEFT JOIN doku_setters ds 
    ON dd.doku_setter_id = ds.setter_id
LEFT JOIN doku_has_variant dhv 
    ON dd.doku_id = dhv.doku_id
LEFT JOIN doku_variants dv 
    ON dhv.doku_variant = dv.variant_id
WHERE dd.doku_date LIKE '$date%'
GROUP BY dd.doku_id
ORDER BY dd.doku_id DESC");

        $dokus = [];
        while ($doku = $query->fetch()) {
            $dokus[] = new DokuDokusMapping($doku);
        }

        if(empty($dokus)) return false;

        return $dokus;
    }
}