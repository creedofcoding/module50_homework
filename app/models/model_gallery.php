<?php

class Model_Gallery extends Model
{
    public function checkCommentsTableExists()
    {
        $checkTable = $this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='comments';");
        if ($checkTable->fetchColumn() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function checkImagesTableExists()
    {
        // Проверяем, существует ли таблица images
        $checkTable = $this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='images';");
        if ($checkTable->fetchColumn() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function getImageById($id)
    {
        // Проверяем существование таблицы comments
        $commentsTableExists = $this->checkCommentsTableExists();

        // Основной запрос
        $query = "
            SELECT 
                images.*, 
                users.name AS uploader_name
        ";

        // Если таблица comments существует, добавляем дополнительные поля
        if ($commentsTableExists) {
            $query .= ",
                comments.id AS comment_id, 
                comments.comment, 
                comments.created_at AS comment_created_at, 
                users_comments.name AS commenter_name
            ";
        }

        $query .= "
            FROM images
            INNER JOIN users ON images.user_id = users.id
        ";

        // Если таблица comments существует, добавляем JOIN
        if ($commentsTableExists) {
            $query .= "
                LEFT JOIN comments ON comments.image_id = images.id
                LEFT JOIN users AS users_comments ON comments.user_id = users_comments.id
            ";
        }

        $query .= " WHERE images.id = :id";

        // Подготовка и выполнение запроса
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImages()
    {
        // Проверяем наличие таблицы
        $imagesTableExists = $this->checkImagesTableExists();

        $images = [];

        if ($imagesTableExists === true) {
            $query = $this->db->query("
                SELECT images.id, images.user_id, images.filename, images.description, images.created_at, users.name
                FROM images
                INNER JOIN users ON images.user_id = users.id
            ");

            $images = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return $images;
    }

    public function saveImage($userId, $filename, $description)
    {
        $stmt = $this->db->prepare("INSERT INTO images (user_id, filename, description, created_at) VALUES (:user_id, :filename, :description, datetime('now', 'localtime'))");

        // Привязка параметров через bindParam
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function deleteImage($imageId, $userId)
    {
        try {
            // Начинаем транзакцию, чтобы обеспечить целостность данных
            $this->db->beginTransaction();

            if ($this->checkCommentsTableExists()) {
                // Удаление комментариев, связанных с изображением
                $stmt = $this->db->prepare("DELETE FROM comments WHERE image_id = :image_id");
                $stmt->bindParam(':image_id', $imageId, PDO::PARAM_INT);
                $stmt->execute();

                // Удаление самого изображения
                $stmt = $this->db->prepare("DELETE FROM images WHERE id = :id AND user_id = :user_id");
                $stmt->bindParam(':id', $imageId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                // Удаление самого изображения
                $stmt = $this->db->prepare("DELETE FROM images WHERE id = :id AND user_id = :user_id");
                $stmt->bindParam(':id', $imageId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Если всё прошло успешно, подтверждаем транзакцию
            $this->db->commit();
        } catch (Exception $e) {
            // Если произошла ошибка, откатываем транзакцию
            $this->db->rollBack();
        }
    }
}
