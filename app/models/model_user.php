<?php
class Model_User extends Model
{
    public function checkUsersTableExists()
    {
        // Проверяем, существует ли таблица images
        $checkTable = $this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users';");
        if ($checkTable->fetchColumn() === false) {
            return false;
        } else {
            return true;
        }
    }

    // Проверяет, есть ли пользователь с таким email
    public function checkUserExists($email)
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return $query->fetchColumn() > 0;
    }

    // Регистрирует нового пользователя
    public function registerUser($email, $name, $password)
    {
        // Проверяем наличие таблицы
        $usersTableExists = $this->checkUsersTableExists();

        if ($usersTableExists) {
            // Хэшируем пароль
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $query = $this->db->prepare("INSERT INTO users (email, name, password, created_at) VALUES (:email, :name, :password, datetime('now', 'localtime'))");
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $query->execute();

            // Возвращаем ID последней вставленной записи
            return $this->db->lastInsertId();
        }
    }

    public function loginUser($email, $password)
    {
        // Находим пользователя по email
        $query = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Пользователь с таким email не найден'];
        }

        // Проверяем пароль
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Неверный пароль'];
        }

        // Успешный вход
        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ];
    }
}
