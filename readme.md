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
- IDE: [VSCode](https://code.visualstudio.com/)
- Локальный веб-сервер: [XAMPP](https://www.apachefriends.org/)
- Программа для работы с БД SQLite: [DB Browser for SQLite](https://sqlitebrowser.org/dl/)
- Браузер: Яндекс

## Как сделать так, чтобы работало?
- **Важно**, чтобы **все файлы** были в папке `module50_homework` по пути `C:\xampp\htdocs\module50_homework\`, иначе **ничего работать не будет!!!**
- Отредактировать файл `httpd-vhosts.conf` по пути `C:\xampp\apache\conf\extra`, вставив туда этот блок кода **(не забудьте сохранить файл!)**:
```
 <VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/module50_homework/public"
    ServerName module50_homework.local
 </VirtualHost>
```
- Далее открыть файл `hosts` **от имени администратора** (например, в [Notepad++](https://notepad-plus-plus.org/downloads/)) по пути `C:\Windows\System32\drivers\etc` и **внизу всего** вставить эту строчку, **попутно не забыв сохранить файл**:
```
127.0.0.1   module50_homework.local
```
- **Перезапускаем XAMPP!**
- **Вуаля! Всё работает!**

## Дополнительно проделанная работа
1. **Переделана логика в файле route.php.** Теперь можно **действительно** писать **все** действия, которые логически должны находиться в одном контроллере, **в один Контроллер**. Это очень удобно, когда нужно написать, например, **один** Контроллер пользователя Controller_User **со всеми действиями**, а не **три отдельных**: Controller_Register, Controller_Login и Controller_Logout.
2. Используется **bootstrap** для стилизации сайта.
3. Проверки у форм осуществляются через **AJAX и JQuery**.
4. Используется **Sweetalert** для вывода ошибок.
5. Используется **toastr** для отображения успешных запросов.
6. Все запросы к БД можно посмотреть [тут](app/database/queries.sql). **Пароль ко всем пользователям единственный - 123**
7. Для того, чтобы **посмотреть отдельное изображение** - нужно просто **кликнуть** на выбранное изображение.