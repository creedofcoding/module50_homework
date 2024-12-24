<?php
class Controller_User extends Controller
{
    function action_register()
    {
        // Стартуем сессию
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        // Устанавливаем заголовок страницы
        $this->view->pageTitle = 'Регистрация';

        // Проверяем, что запрос POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Обращаемся к модели для регистрации
            $userModel = new Model_User();

            // Проверка существования таблицы users
            if ($userModel->checkUsersTableExists() === false) {
                http_response_code(500); // Устанавливаем статус ответа 500
                exit();
            }

            if ($userModel->checkUserExists($email)) {
                echo json_encode(
                    [
                        'success' => false,
                        'message' => 'Этот email уже зарегистрирован',
                        'errors' => [
                            'email' => 'Этот email уже зарегистрирован'
                        ]
                    ],
                    JSON_UNESCAPED_UNICODE
                );
                exit();
            }

            $userId = $userModel->registerUser($email, $name, $password);

            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Вы успешно зарегистрировались'
                ],
                JSON_UNESCAPED_UNICODE
            );

            // В контроллере при успешной регистрации
            $_SESSION['notification'] = [
                'type' => 'success', // Тип уведомления ('success', 'error', 'info', 'warning')
                'message' => 'Вы успешно зарегистрировались!' // Сообщение уведомления
            ];

            // Записываем данные в сессию
            $this->storeUserInSession($userId, $email, $name);

            exit();
        }

        // Генерируем представление с переданными данными
        $this->view->render('register_view.php', 'default_layout.php');
    }

    function action_login()
    {
        // Стартуем сессию
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        // Устанавливаем заголовок страницы
        $this->view->pageTitle = 'Вход';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $userModel = new Model_User();

            // Проверка существования таблицы users
            if ($userModel->checkUsersTableExists() === false) {
                http_response_code(500); // Устанавливаем статус ответа 500
                exit();
            }

            $loginResult = $userModel->loginUser($email, $password);

            if (!$loginResult['success']) {
                echo json_encode(
                    [
                        'success' => false,
                        'message' => $loginResult['message']
                    ],
                    JSON_UNESCAPED_UNICODE
                );
                exit();
            }

            // Сохраняем пользователя в сессию
            $this->storeUserInSession(
                $loginResult['user']['id'],
                $loginResult['user']['email'],
                $loginResult['user']['name']
            );

            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Вы успешно вошли'
                ],
                JSON_UNESCAPED_UNICODE
            );

            $_SESSION['notification'] = [
                'type' => 'success', // Тип уведомления ('success', 'error', 'info', 'warning')
                'message' => 'Вы успешно вошли!' // Сообщение уведомления
            ];

            exit();
        }

        // Генерируем представление
        $this->view->render('login_view.php', 'default_layout.php');
    }

    function action_logout()
    {
        // Стартуем сессию
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //проверка на существующую сессию
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }

        echo json_encode(
            [
                'success' => true,
                'message' => 'Вы успешно вышли!'
            ],
            JSON_UNESCAPED_UNICODE
        );

        $_SESSION['notification'] = [
            'type' => 'success', // Тип уведомления ('success', 'error', 'info', 'warning')
            'message' => 'Вы успешно вышли!' // Сообщение уведомления
        ];

        // Удаляем данные пользователя из сессии
        unset($_SESSION['user']);

        // Перенаправляем на главную страницу
        header("Location: /");
        exit();
    }

    private function storeUserInSession($id, $email, $name)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user'] = [
            'id' => $id,
            'email' => $email,
            'name' => $name
        ];
    }
}
