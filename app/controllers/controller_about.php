<?php
class Controller_About extends Controller
{
    function action_index()
    {
        // Устанавливаем заголовок страницы
        $this->view->pageTitle = 'О нас';

        // Генерируем представление с переданными данными
        $this->view->render('about_view.php', 'default_layout.php');
    }
}
