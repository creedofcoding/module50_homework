# Галерея изображений (HW-04)

## Практическое задание к модулю 50
В качестве практической работы мы создадим галерею изображений:
- Авторизованный пользователь может загрузить файл и сохранить на сервер. Неавторизованный имеет только возможность просмотра галереи.
- К файлам нужно создать возможность оставлять комментарии через форму только для авторизованных пользователей, а незарегистрированные пользователи просто могут видеть комментарии. Поля должны быть следующими: текст, автор, дата создания комментария.
- Авторизованный пользователь также может и удалить свой комментарий. Сделайте удаление изображения, при его удалении комментарии также удаляются из БД.

## Критерии оценки выполненного задания
**0 баллов**
- не выполнены условия на отметку 5.

**5 баллов**
- создана папка с проектом в нужной директории и все необходимые в этой папке файлы;
- в файле config.php определены константы для размера файлов, типа файлов, localhost, пути загрузки изображений и комментариев;
- в index.php (или другое название, на ваш вкус, файла) записан код для загрузки файлов;

**10 баллов**
- всё, что требуется на 5 баллов;
- создана основная верстка и добавлены стили;
- файлы загружаются и выводится галерея;
- добавлен код для возможности удаления файлов;

**15 баллов**
- всё, что требуется на 10 баллов;
- создана форма отправки комментариев;

**20 баллов**
- всё, что требуется на 15 баллов;
- добавлен код для работы с комментариями, и комментарии, отправляемые через форму — сохраняются и выводятся к изображению;
- есть возможность удалить файл и комментарий.

## Используемое ПО
- OS: Windows
- IDE: VSCode
- Локальный веб-сервер: XAMPP

## Дополнительно проделанная работа
1. **Переделана логика в файле route.php.** Теперь можно **действительно** писать **все** действия, которые логически должны находиться в одном контроллере, **в один Контроллер**. Это очень удобно, когда нужно написать, например, **один** Контроллер пользователя Controller_User **со всеми действиями**, а не **три отдельных**: Controller_Register, Controller_Login и Controller_Logout.
2. **Используется bootstrap с JQuery** для стилизации сайта.
3. Проверки у форм осуществляются через **AJAX**.
4. Используется **Sweetalert** для вывода ошибок.
5. Используется **toastr** для отображения успешных запросов.
6. Все запросы к БД можно посмотреть [тут](app/database/queries.sql). **Пароль ко всем пользователям единственный - 123**
7. Для того, чтобы **посмотреть отдельное изображение** - нужно просто **кликнуть** на выбранное изображение.