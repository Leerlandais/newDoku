<?php

namespace model\Mapping;

use DateTime;
use model\Abstract\AbstractMapping;

class DokuCommentsMapping extends AbstractMapping
{
    private int $comment_id;
    private int $comment_doku_id;
    private string $comment_text;

    public function getCommentId(): int
    {
        return $this->comment_id;
    }

    public function setCommentId(int $comment_id): void
    {
        $this->comment_id = $comment_id;
    }

    public function getCommentDokuId(): int
    {
        return $this->comment_doku_id;
    }

    public function setCommentDokuId(int $comment_doku_id): void
    {
        $this->comment_doku_id = $comment_doku_id;
    }

    public function getCommentText(): string
    {
        return htmlspecialchars_decode($this->comment_text);
    }

    public function setCommentText(string $comment_text): void
    {
        $this->comment_text = $comment_text;
    }


}