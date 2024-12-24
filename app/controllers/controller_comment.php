<?php

class Controller_Comment extends Controller
{
    public function action_add_comment()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //проверка на существующую сессию
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'], $_POST['image_id'], $_POST['comment'])) {
            $userId = $_SESSION['user']['id'];
            $imageId = (int)$_POST['image_id'];
            $comment = trim($_POST['comment']);

            if ($comment !== '') {
                $commentModel = new Model_Comment();
                $commentModel->addComment($userId, $imageId, $comment);

                echo json_encode(
                    [
                        'success' => true,
                        'message' => 'Комментарий успешно добавлен!'
                    ],
                    JSON_UNESCAPED_UNICODE
                );

                $_SESSION['notification'] = [
                    'type' => 'success', // Тип уведомления ('success', 'error', 'info', 'warning')
                    'message' => 'Комментарий успешно добавлен!' // Сообщение уведомления
                ];
            }
        } else {
            header('Location: /gallery');
            exit;
        }

        header('Location: /view_image?id=' . (int)$_POST['image_id']);
        exit;
    }

    public function action_delete_comment()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //проверка на существующую сессию
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'], $_POST['comment_id'])) {
            $userId = $_SESSION['user']['id'];
            $commentId = (int)$_POST['comment_id'];

            $commentModel = new Model_Comment();
            $commentModel->deleteComment($commentId, $userId);

            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Комментарий успешно удалён!'
                ],
                JSON_UNESCAPED_UNICODE
            );

            $_SESSION['notification'] = [
                'type' => 'success', // Тип уведомления ('success', 'error', 'info', 'warning')
                'message' => 'Комментарий успешно удалён!' // Сообщение уведомления
            ];
        } else {
            header('Location: /gallery');
            exit;
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
