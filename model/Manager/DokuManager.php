<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\DokuDokusMapping;
use model\Mapping\DokuSettersMapping;
use model\Mapping\DokuVariantsMapping;

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

    public function getTotalTime($current = false) : int
    {
        $today = $current ? "WHERE doku_date LIKE '".date("Y-m-d")."%'" : "";
        $query = $this->db->query("SELECT SUM(doku_duration) AS total_time FROM doku_dokus $today");
        $query->execute();
        $totalTime = $query->fetch();
        $query->closeCursor();
        return $totalTime["total_time"];
    }

    public function getAllPuzzles() : array|false
    {
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
GROUP BY dd.doku_id
ORDER BY dd.doku_id DESC");
        $dokus = [];
        while ($doku = $query->fetch()) {
            $dokus[] = new DokuDokusMapping($doku);
        }
        if(empty($dokus)) return false;
        return $dokus;
    }

    public function getAllSetters() : array|false
    {
        $query = $this->db->query("SELECT * FROM doku_setters ORDER BY setter_name");
        $setters = [];
        while ($setter = $query->fetch()) {
            $setters[] = new DokuSettersMapping($setter);
        }
        if(empty($setters)) return false;
        return $setters;
    }
    public function addSetter(array $data) : bool
    {
        return $this->insertAnything($data, "doku_setters");
    }

    public function getAllVariants() : array|false
    {
        $query = $this->db->query("SELECT * FROM doku_variants ORDER BY variant_name");
        $variants = [];
        while ($variant = $query->fetch()) {
            $variants[] = new DokuVariantsMapping($variant);
        }
        if(empty($variants)) return false;
        return $variants;
    }

    public function addVariant(array $data) : bool
    {
        return $this->insertAnything($data, "doku_variants");
    }

    public function addSudoku(array $data) : bool|int
    {
        $data["doku_date"] = date("Y-m-d H:i:s");
        return $this->insertAnything($data, "doku_dokus","db", true);
    }

    public function addSudokuVariantsToLatestPuzzle(array $datas) : void
    {
        $dokuId = $datas["doku_id"];
        unset($datas["doku_id"]);
        foreach ($datas as $data) {
            $stmt = $this->db->prepare("INSERT INTO doku_has_variant (doku_id, doku_variant) VALUES (:doku_id, :variant_id)");
            $stmt->bindParam(":doku_id", $dokuId);
            $stmt->bindParam(":variant_id", $data);
            $stmt->execute();
        }
    }
}