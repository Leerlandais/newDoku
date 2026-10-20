<?php

namespace model\Manager;

use model\Abstract\AbstractManager;

class AdminManager extends AbstractManager
{
    public function addClue(array $data) : bool
    {
        return $this->insertAnything($data, "doku_clues");
    }


    public function updateStars(array $data) : void
    {
        $this->updateAnything($data, "doku_id", $data["doku_id"], "doku_dokus");
    }
    public function addVariant(array $data) : bool
    {
        return $this->insertAnything($data, "doku_variants");
    }

    public function editVariant(array $data) : bool
    {
        $id = $data["variant_id"];
        unset($data["variant_id"]);
        return $this->updateAnything($data, "variant_id", $id , "doku_variants");
    }

    public function addSudoku(array $data) : bool|int
    {
        $data["doku_date"] = date("Y-m-d H:i:s");
        return $this->insertAnything($data, "doku_dokus","db", true);
    }
    public function addSetter(array $data) : bool
    {
        return $this->insertAnything($data, "doku_setters");
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
    public function incremementSetterCount(int $setter) : void
    {
        $stmt = $this->db->prepare("UPDATE doku_setters SET setter_count = setter_count + 1 WHERE setter_id = :setter");
        $stmt->bindParam(":setter", $setter, \PDO::PARAM_INT);
        $stmt->execute();

    }


    public function uploadImage($data): bool
    {
        $target_dir = IMG_DIR . "/";
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
    public function incrementSolve(int $id) : void
    {
        $stmt = $this->db->prepare("UPDATE doku_dokus SET doku_solve_count = doku_solve_count + 1 WHERE doku_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
    }


}