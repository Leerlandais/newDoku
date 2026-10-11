<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\DokuDokusMapping;

class SearchManager extends AbstractManager
{
    public function getPuzzleByTitle(string $title): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT dd.*, ds.setter_name AS doku_setter_name,
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
                WHERE dd.doku_title LIKE :title
                GROUP BY dd.doku_id;");
        $stmt->execute(["title" => "%$title%"]);
        $results = [];
        while ($result = $stmt->fetch()) {

            $results[] = new DokuDokusMapping($result);
        }
        if (empty($results)) return false;
        return $results;
    }

    public function getPuzzlesBySetter(int $setterId): array|false
    {
        $stmt = $this->db->prepare("
                SELECT dd.*, ds.setter_name AS doku_setter_name,
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
        if (empty($results)) return false;
        return $results;
    }

    public function getPuzzlesByDuration(int $duration) : array|false
    {
        $stmt = $this->db->prepare("
            SELECT dd.*, ds.setter_name AS doku_setter_name,
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
                WHERE dd.doku_duration <= :duration
                GROUP BY dd.doku_id
                ORDER BY dd.doku_id DESC");
        $stmt->bindParam(":duration", $duration, \PDO::PARAM_INT);
        $stmt->execute();
        $results = [];
        while ($result = $stmt->fetch()) {
            $results[] = new DokuDokusMapping($result);
        }
        if (empty($results)) return false;
        return $results;
    }

    public function getPuzzleByStars(int $stars, bool $min = false) : array|false
    {
        if($min) $whereParams = "dd.doku_stars >= :stars";
        else $whereParams = "dd.doku_stars <= :stars";
        $stmt = $this->db->prepare("
            SELECT 
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
                WHERE $whereParams
                GROUP BY dd.doku_stars
                ORDER BY dd.doku_stars DESC");
        $stmt->bindParam(":stars", $stars, \PDO::PARAM_INT);
        $stmt->execute();
        $results = [];
        while ($result = $stmt->fetch()) {
            $results[] = new DokuDokusMapping($result);
        }
        if (empty($results)) return false;
        return $results;
    }

    public function getPuzzlesByVariant(array $variantIds) : array|false
    {
        $stmt = $this->db->prepare("
            SELECT * FROM doku_has_variant WHERE doku_variant IN (:variant_ids)");
                 $implode = implode(", ", $variantIds);
                 $stmt->bindParam(":variant_ids", $implode, \PDO::PARAM_STR);
                 $stmt->execute();
                 $results = $stmt->fetchAll();
                 if(empty($results)) return false;
                 $puzzles = [];
                 foreach ($results as $result) {
            $stmt = $this->db->prepare("
                    SELECT 
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

    public function getPuzzlesByVariantId(int $variantId) : array|false
    {
        $stmt = $this->db->prepare("
            SELECT * FROM doku_has_variant WHERE doku_variant = :variant_id");

        $stmt->bindParam(":variant_id",$variantId);
        $stmt->execute();
        $results = $stmt->fetchAll();
        if(empty($results)) return false;
        $puzzles = [];
        foreach ($results as $result) {
            $stmt = $this->db->prepare("
                    SELECT 
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

    public function getPuzzlesByDate($startDate, $endDate) : array|false
    {
        $start = substr($startDate, 0, 10);
        $end = substr($endDate, 0, 10);
        $stmt = $this->db->prepare("
                    SELECT 
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
                    WHERE dd.doku_date BETWEEN :start AND :end
                    GROUP BY dd.doku_id
                    ORDER BY dd.doku_id DESC");
        $stmt->bindParam(":start", $start);
        $stmt->bindParam(":end", $end);
        $stmt->execute();
        $puzzles = [];
        while ($result = $stmt->fetch()) {
            $puzzles[] = new DokuDokusMapping($result);
        }
        if(empty($puzzles)) return false;
        return $puzzles;
    }
}