<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\DokuDokusMapping;

class DokuManager extends AbstractManager
{
    public function getLatestPuzzles() : array|false
    {
        $date = date("Y-m-d");
        $query = $this->db->query("SELECT dd.*, ds.setter_name AS 'doku_setter_name'  FROM doku_dokus dd 
                                            LEFT JOIN doku_setters ds ON dd.doku_setter_id = ds.setter_id
                                            WHERE dd.doku_date LIKE '$date%'
                                
                                            ORDER BY doku_id DESC");

        $dokus = [];
        while ($doku = $query->fetch()) {
            $dokus[] = new DokuDokusMapping($doku);
        }

        if(empty($dokus)) return false;
        return $dokus;
    }
}