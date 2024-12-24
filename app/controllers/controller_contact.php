<?php
class Controller_Contact extends Controller
{
    function action_index()
    {
        // Устанавливаем заголовок страницы
        $this->view->pageTitle = 'Контакты';

        // Генерируем представление с переданными данными
        $this->view->render('contact_view.php', 'default_layout.php');
    }
}
