<?php
class Controller_Gallery extends Controller
{
    function action_gallery()
    {
        // Устанавливаем заголовок страницы
        $this->view->pageTitle = 'Галерея изображений';

        $galleryModel = new Model_Gallery();
        $data = $galleryModel->getImages();

        // Генерируем представление с переданными данными
        $this->view->render('gallery_view.php', 'default_layout.php', $data);
    }

    function action_upload_images()
    {
        // Стартуем сессию
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        // Устанавливаем заголовок страницы
        $this->view->pageTitle = 'Загрузка изображений';

        if (!file_exists(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0777, true);
        }

        // Проверка наличия файлов
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file']['name'][0])) {
            header('Content-Type: application/json');

            $userId = $_SESSION['user']['id'];
            $description = $_POST['description'] ?? '';
            $uploadDir = realpath(UPLOAD_DIR) . DIRECTORY_SEPARATOR;

            //echo $uploadDir . '<br/>';

            $galleryModel = new Model_Gallery();

            // Проверка существования таблицы images
            if ($galleryModel->checkImagesTableExists() === false) {
                http_response_code(500); // Устанавливаем статус ответа 500
                echo json_encode(['success' => false]);
                exit;
            }

            $invalidFiles = [];
            $validFiles = [];

            // Перебор файлов
            foreach ($_FILES['file']['name'] as $key => $filename) {
                $tmpName = $_FILES['file']['tmp_name'][$key];
                $fileType = mime_content_type($tmpName); // Определение MIME-типа

                // Проверяем допустимый тип
                if (!in_array($fileType, ALLOWED_TYPES)) {
                    $errors[] = "Файл {$filename} имеет недопустимое расширение: {$fileType}.";
                    $invalidFiles[] = $filename;
                    continue;
                }

                $baseName = pathinfo($filename, PATHINFO_FILENAME);
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $newFilePath = $uploadDir . $filename;

                // Проверяем, существует ли файл, и добавляем суффикс
                $counter = 1;
                while (file_exists($newFilePath)) {
                    $newFileName = $baseName . ' ' . $counter . '.' . $extension;
                    $newFilePath = $uploadDir . $newFileName;
                    $counter++;
                }

                // Перемещаем файл
                if (move_uploaded_file($tmpName, $newFilePath)) {
                    $savedFileName = basename($newFilePath);
                    $galleryModel->saveImage($userId, $savedFileName, $description);
                    $validFiles[] = $savedFileName;
                } else {
                    $invalidFiles[] = $filename;
                }
            }

            // Возвращаем JSON-ответ
            if (!empty($invalidFiles)) {
                if (!empty($validFiles)) {
                    echo json_encode(['success' => false, 'error' =>
                    "<span style='color: red;'>Ошибка при загрузке следующих файлов: " . implode(', ', $invalidFiles) . "</span><br/>" .
                        "<span style='color: green;'>Успешно загруженные файлы: " . implode(', ', $validFiles) . "</span><br/>" .
                        "<b>Чтобы увидеть галерею, перейдите по <a href='/gallery'>ссылке</a></b>"]);
                } else {
                    echo json_encode(['success' => false, 'error' => "<span style='color: red;'>Ошибка при загрузке всех файлов: " . implode(', ', $invalidFiles) . "</span>"]);
                }

                http_response_code(400); // Ошибка
            } else {
                echo json_encode(
                    [
                        'success' => true,
                        'message' => 'Все файлы успешно загружены!'
                    ],
                    JSON_UNESCAPED_UNICODE
                );
    
                // В контроллере при успешной регистрации
                $_SESSION['notification'] = [
                    'type' => 'success', // Тип уведомления ('success', 'error', 'info', 'warning')
                    'message' => 'Все файлы успешно загружены!' // Сообщение уведомления
                ];
            }

            return;
        }

        // Генерируем представление с переданными данными
        $this->view->render('upload_image_view.php', 'default_layout.php');
    }

    function action_view_image()
    {
        // Стартуем сессию
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $galleryModel = new Model_Gallery();

        // Проверка существования таблицы images
        if ($galleryModel->checkImagesTableExists() === false) {
            http_response_code(500); // Устанавливаем статус ответа 500
            echo json_encode(['success' => false]);
            header('Location: /gallery');
            exit;
        }

        if (!isset($_GET['id'])) {
            header('Location: /gallery');
            exit;
        }

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $imageId = (int)$_GET['id'];
            $imageData = $galleryModel->getImageById($imageId);

            if (empty($imageData)) {
                // Если изображение не найдено, перенаправляем в галерею
                header('Location: /gallery');
                exit;
            }

            // Перебираем полученные данные для разделения изображения и комментариев
            $imageInfo = $imageData[0];  // Изображение (первый элемент массива)
            $comments = [];

            // Собираем комментарии из данных
            foreach ($imageData as $data) {
                if ($data['comment_id']) {
                    $comments[] = [
                        'id' => $data['comment_id'],
                        'comment' => $data['comment'],
                        'created_at' => $data['comment_created_at'],
                        'commenter_name' => $data['commenter_name']
                    ];
                }
            }

            $commentsTableExists = $galleryModel->checkCommentsTableExists();

            // Передаем данные в представление
            $this->view->pageTitle = 'Детали изображения';
            $this->view->render('image_details_view.php', 'default_layout.php', [
                'data' => $imageInfo,  // Сами данные изображения
                'comments' => $comments, // Комментарии
                'commentsTableExists' => $commentsTableExists //существование таблицы comments
            ]);
        } else {
            // Если ID изображения не передан или некорректен
            header('Location: /gallery');
            exit;
        }
    }

    function action_delete_image()
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']['id'], $_POST['image_id'])) {
            $userId = $_SESSION['user']['id'];
            $imageId = (int)$_POST['image_id'];

            $galleryModel = new Model_Gallery();

            // Получаем информацию об изображении
            $image = $galleryModel->getImageById($imageId);
            $image = $image[0];  // Изображение (первый элемент массива)

            if ($image && $image['user_id'] == $userId) {
                $filePath = realpath(UPLOAD_DIR) . DIRECTORY_SEPARATOR . $image['filename'];

                // Удаляем файл
                if (file_exists($filePath) && unlink($filePath)) {
                    // Удаляем запись из базы данных
                    $galleryModel->deleteImage($imageId, $userId);
                    
                    echo json_encode(
                        [
                            'success' => true,
                            'message' => 'Изображение успешно удалено!'
                        ],
                        JSON_UNESCAPED_UNICODE
                    );
            
                    $_SESSION['notification'] = [
                        'type' => 'success', // Тип уведомления ('success', 'error', 'info', 'warning')
                        'message' => 'Изображение успешно удалено!' // Сообщение уведомления
                    ];
                } else if (!file_exists($filePath)) {
                    // Удаляем запись из базы данных
                    $galleryModel->deleteImage($imageId, $userId);
                    echo json_encode(['success' => true, 'message' => 'Запись в БД удалена.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Ошибка при удалении изображения.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ошибка: изображение не найдено или у вас нет прав на его удаление.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка: некорректный запрос.']);
        }

        // Перенаправляем на главную страницу
        header("Location: /gallery");
        exit();
    }
}
