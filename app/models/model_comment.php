<?php

class Model_Comment extends Model
{
    /* public function checkCommentsTableExists()
    {
        // Проверяем, существует ли таблица images
        $checkTable = $this->db->query("SELECT * FROM sqlite_master WHERE type='table' AND name='comments';");
        if ($checkTable->fetchColumn() === false) {
            return false;
        } else {
            return true;
        }
    } */

    public function addComment($userId, $imageId, $comment)
    {
        $stmt = $this->db->prepare("
            INSERT INTO comments (user_id, image_id, comment, created_at)
            VALUES (:user_id, :image_id, :comment, datetime('now', 'localtime'))
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':image_id', $imageId, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deleteComment($commentId, $userId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM comments
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->bindParam(':id', $commentId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
