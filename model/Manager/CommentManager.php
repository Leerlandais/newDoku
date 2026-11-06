<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\DokuCommentsMapping;

class CommentManager extends AbstractManager
{
    public function addComment(array $data) : bool
    {
        return $this->insertAnything($data, "doku_comments");
    }

    public function getDokuComments(int $id) : array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM doku_comments WHERE comment_doku_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $comments = [];
        while ($comment = $stmt->fetch()) {
            $comments[] = new DokuCommentsMapping($comment);
        }
        if(empty($comments)) return false;
        return $comments;
    }
}