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
        $stmt = $this->db->prepare("SELECT 
    dd.*,
    ds.setter_name AS doku_setter_name,
    GROUP_CONCAT(DISTINCT dv.variant_name ORDER BY dv.variant_name SEPARATOR ', ') AS doku_variant_names
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
$stmt->execute();
        $dokus = [];
        while ($doku = $stmt->fetch()) {
            if(!empty($doku)) {
                $dokus[] = new DokuDokusMapping($doku);
            }
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
        return $totalTime["total_time"] ?? 0;
    }

    public function getAllPuzzles(int $pageCounter) : array|false
    {
        $offset = ($pageCounter - 1) * 10;

        $query = $this->db->prepare("SELECT 
    dd.*,
    ds.setter_name AS doku_setter_name,
    GROUP_CONCAT(DISTINCT dv.variant_name ORDER BY dv.variant_name SEPARATOR ', ') AS doku_variant_names
FROM doku_dokus dd
LEFT JOIN doku_setters ds 
    ON dd.doku_setter_id = ds.setter_id
LEFT JOIN doku_has_variant dhv 
    ON dd.doku_id = dhv.doku_id
LEFT JOIN doku_variants dv 
    ON dhv.doku_variant = dv.variant_id
WHERE dd.doku_id > :offset
GROUP BY dd.doku_id
ORDER BY dd.doku_id LIMIT 10");
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();
        $dokus = [];
        while ($doku = $query->fetch()) {
            $dokus[] = new DokuDokusMapping($doku);
        }
        if(empty($dokus)) return false;
        return $dokus;
    }

    public function getPuzzleById(int $id) : DokuDokusMapping|false
    {
        $stmt = $this->db->prepare("SELECT 
    dd.*, dhi.img_location AS doku_img_location,
    ds.setter_name AS doku_setter_name,
    GROUP_CONCAT(DISTINCT dv.variant_name ORDER BY dv.variant_name SEPARATOR ', ') AS doku_variant_names
FROM doku_dokus dd
LEFT JOIN doku_setters ds 
    ON dd.doku_setter_id = ds.setter_id
LEFT JOIN doku_has_variant dhv 
    ON dd.doku_id = dhv.doku_id
LEFT JOIN doku_variants dv 
    ON dhv.doku_variant = dv.variant_id
LEFT JOIN doku_has_image dhi 
ON dd.doku_id = dhi.doku_id
    WHERE dd.doku_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $doku = $stmt->fetch();
        if(!$doku) return false;
        return new DokuDokusMapping($doku);
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
    
    public function getPuzzlesByVariant(array $variantIds) : array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM doku_has_variant WHERE doku_variant IN (:variant_ids)");
        $implode = implode(", ", $variantIds);
        $stmt->bindParam(":variant_ids", $implode, \PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll();
        if(empty($results)) return false;
        $puzzles = [];
            foreach ($results as $result) {
                $stmt = $this->db->prepare("SELECT 
    dd.*,
    ds.setter_name AS doku_setter_name,
    GROUP_CONCAT(DISTINCT dv.variant_name ORDER BY dv.variant_name SEPARATOR ', ') AS doku_variant_names
FROM doku_dokus dd
LEFT JOIN doku_setters ds 
    ON dd.doku_setter_id = ds.setter_id
LEFT JOIN doku_has_variant dhv 
    ON dd.doku_id = dhv.doku_id
LEFT JOIN doku_variants dv 
    ON dhv.doku_variant = dv.variant_id
WHERE dd.doku_id = :doku_id
GROUP BY dd.doku_id
ORDER BY dd.doku_id DESC");
                $stmt->bindParam(":doku_id", $result["doku_id"], \PDO::PARAM_INT);
                $stmt->execute();
                $data = $stmt->fetch();
                if($data) $puzzles[] = new DokuDokusMapping($data);
            }
            return $puzzles;
    }

    public function getPuzzlesBySetter(int $setterId) : array|false
    {
        $stmt = $this->db->prepare("SELECT 
    dd.*,
    ds.setter_name AS doku_setter_name,
    GROUP_CONCAT(
        DISTINCT dv.variant_name 
        ORDER BY dv.variant_name 
        SEPARATOR ', '
    ) AS doku_variant_names
FROM doku_dokus dd
LEFT JOIN doku_setters ds 
    ON dd.doku_setter_id = ds.setter_id
LEFT JOIN doku_has_variant dhv 
    ON dd.doku_id = dhv.doku_id
LEFT JOIN doku_variants dv 
    ON dhv.doku_variant = dv.variant_id
WHERE dd.doku_setter_id = :setter_id
GROUP BY dd.doku_id;");
        $stmt->bindParam(":setter_id", $setterId, \PDO::PARAM_INT);
        $stmt->execute();

        $results = [];
        while ($result = $stmt->fetch()) {
            $results[] = new DokuDokusMapping($result);
        }
        if(empty($results)) return false;
        return $results;
    }

    public function getPuzzlesByDuration(int $duration) : array|false
    {
        $stmt = $this->db->prepare("SELECT 
    dd.*,
    ds.setter_name AS doku_setter_name,
    GROUP_CONCAT(DISTINCT dv.variant_name ORDER BY dv.variant_name SEPARATOR ', ') AS doku_variant_names
    FROM doku_dokus dd
LEFT JOIN doku_setters ds 
ON dd.doku_setter_id = ds.setter_id
LEFT JOIN doku_has_variant dhv 
ON dd.doku_id = dhv.doku_id
LEFT JOIN doku_variants dv 
ON dhv.doku_variant = dv.variant_id
WHERE dd.doku_duration < :duration
GROUP BY dd.doku_id
ORDER BY dd.doku_id DESC");
        $stmt->bindParam(":duration", $duration, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        if(empty($results)) return false;
        $puzzles = [];
        foreach ($results as $result) {
            $puzzles[] = new DokuDokusMapping($result);
        }
        return $puzzles;
    }

    public function countPuzzlesByVariant() : array
    {
        $query = $this->db->query("SELECT COUNT(doku_id) AS count, doku_variant FROM doku_has_variant GROUP BY doku_variant");
        $results = [];
        while ($result = $query->fetch()) {
            $results[] = $result;
        }

        return $results;
    }

    public function incremementSetterCount(int $setter) : void
    {
        $stmt = $this->db->prepare("UPDATE doku_setters SET setter_count = setter_count + 1 WHERE setter_id = :setter");
        $stmt->bindParam(":setter", $setter, \PDO::PARAM_INT);
        $stmt->execute();

    }

    public function getPuzzleCount() : int
    {
        $query = $this->db->query("SELECT COUNT(doku_id) AS count FROM doku_dokus");
        $result = $query->fetch();
        return $result["count"];
    }

    public function getPuzzlesByDate(?string $start, ?string $end) : array|false
    {
        if(!$start || !$end) {
            if (!$start) $start = "2026-09-06";
            if (!$end) $end = date("Y-m-d");
        }else {
            $start = date("Y-m-d", strtotime($start));
            $end = date("Y-m-d", strtotime($end));
        }
        $stmt = $this->db->prepare("SELECT 
    dd.*,
    ds.setter_name AS doku_setter_name,
    GROUP_CONCAT(
        DISTINCT dv.variant_name 
        ORDER BY dv.variant_name 
        SEPARATOR ', '
    ) AS doku_variant_names
FROM doku_dokus dd
LEFT JOIN doku_setters ds 
    ON dd.doku_setter_id = ds.setter_id
LEFT JOIN doku_has_variant dhv 
    ON dd.doku_id = dhv.doku_id
LEFT JOIN doku_variants dv 
    ON dhv.doku_variant = dv.variant_id
WHERE dd.doku_date >= :start AND dd.doku_date <= :end
GROUP BY dd.doku_id");
        $stmt->bindParam(":start", $start, \PDO::PARAM_STR);
        $stmt->bindParam(":end", $end, \PDO::PARAM_STR);
        $stmt->execute();
        $results = [];
        while ($result = $stmt->fetch()) {
            $results[] = new DokuDokusMapping($result);
        }
        if(empty($results)) return false;
        return $results;
    }

    public function uploadImage($data): bool
    {
        $target_dir = ".." . IMG_DIR;
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = false;


            if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
                $uploadOk = true;
            }


        return $uploadOk;
    }

    public function addImageToDatabase(array $insertInfo) : void
    {
        $this->insertAnything($insertInfo, "doku_has_image");
    }
}