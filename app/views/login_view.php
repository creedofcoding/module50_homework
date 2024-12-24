<div class="row justify-content-center mt-4">
    <div class="col-4">
        <form id="login-form" action="/login" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Введите email">
                <div class="form-control-feedback" id="email-feedback"></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
                <div class="form-control-feedback" id="password-feedback"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-4">Войти</button>
            <div class="col-12 text-center">
                <p class="small mb-0">Нет аккаунта? <a href="/register">Зарегистрироваться</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#login-form').on('submit', function(e) {
            e.preventDefault();

            // Сбрасываем ошибки
            $('.form-control-feedback').text('');
            $('input').removeClass('is-invalid');

            // Получаем значения из полей
            var email = $('#email').val();
            var password = $('#password').val();

            // Проверка
            if (!email || !password) {
                if (!email) {
                    $('#email-feedback').text('Email не может быть пустым');
                    $('#email').addClass('is-invalid');
                } else {
                    $('#email').addClass('is-valid');
                }

                if (!password) {
                    $('#password-feedback').text('Пароль не может быть пустым');
                    $('#password').addClass('is-invalid');
                } else {
                    $('#password').addClass('is-valid');
                }

                return;
            }

            var formData = $(this).serialize();
            formData = decodeURIComponent(formData);
            //console.log(formData);

            $.ajax({
                url: '/login',
                method: 'POST',
                data: formData,
                success: function(response) {
                    const parsedResponse = JSON.parse(response);
                    //console.log(parsedResponse);

                    if (!parsedResponse.success) {
                        if (parsedResponse.message === 'Пользователь с таким email не найден') {
                            $('#email-feedback').text(parsedResponse.message);
                            $('#email').addClass('is-invalid');
                        } else if (parsedResponse.message === 'Неверный пароль') {
                            $('#password-feedback').text(parsedResponse.message);
                            $('#password').addClass('is-invalid');
                        }
                        return;
                    }

                    // Перенаправляем пользователя на главную страницу
                    window.location.href = '/';
                },
                error: function(xhr, status, error) {
                    // Сбрасываем старые ошибки и классы
                    $('.form-control-feedback').text('');
                    $('input').removeClass('is-invalid is-valid');

                    $('#email').addClass('is-invalid');
                    $('#password').addClass('is-invalid');
                    
                    // Если ошибка на сервере, выводим ее через SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Произошла ошибка',
                        text: `Ошибка сервера: ${error}.`,
                    });

                    // Или можно использовать стандартный alert
                    //console.log('Произошла ошибка при отправке данных: ' + xhr.status + ': ' + xhr.statusText);
                }
            });
        });
    });
</script>