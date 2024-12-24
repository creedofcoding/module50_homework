<div class="row justify-content-center mt-4">
    <div class="col-4">
        <form id="registration-form" action="/register" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Введите email">
                <div class="form-control-feedback" id="email-feedback"></div>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Имя</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Введите имя">
                <div class="form-control-feedback" id="name-feedback"></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
                <div class="form-control-feedback" id="password-feedback"></div>
            </div>
            <div class="mb-3">
                <label for="repeat-password" class="form-label">Повторите пароль</label>
                <input type="password" class="form-control" id="repeat-password" name="repeat-password" placeholder="Повторите пароль">
                <div class="form-control-feedback" id="repeat-password-feedback"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-4">Зарегистрироваться</button>
            <div class="col-12 text-center">
                <p class="small mb-0">Уже есть аккаунт? <a href="/login">Войти</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#registration-form').on('submit', function(e) {
            e.preventDefault(); // Предотвращаем стандартную отправку формы

            // Сбрасываем старые ошибки и классы
            $('.form-control-feedback').text('');
            $('input').removeClass('is-invalid is-valid');

            // Получаем значения из полей
            var email = $('#email').val();
            var name = $('#name').val();
            var password = $('#password').val();
            var repeatPassword = $('#repeat-password').val();

            // Регулярное выражение для проверки email и проверка
            var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            var emailValid = emailRegex.test(email);

            // Проверка
            if (!email || !name || !password || !repeatPassword) {
                if (!email) {
                    $('#email-feedback').text('Email не может быть пустым');
                    $('#email').addClass('is-invalid');
                } else if (!emailValid) {
                    $('#email-feedback').text('Некорректный email');
                    $('#email').addClass('is-invalid');
                } else {
                    $('#email').addClass('is-valid');
                }

                if (!name) {
                    $('#name-feedback').text('Имя не может быть пустым');
                    $('#name').addClass('is-invalid');
                } else {
                    $('#name').addClass('is-valid');
                }

                if (!password) {
                    $('#password-feedback').text('Пароль не может быть пустым');
                    $('#password').addClass('is-invalid');
                } else {
                    $('#password').addClass('is-valid');
                }

                if (!repeatPassword) {
                    $('#repeat-password-feedback').text('Это поле не может быть пустым');
                    $('#repeat-password').addClass('is-invalid');
                }

                return;
            }

            // Проверка на совпадение паролей
            if (password !== repeatPassword) {
                $('#password-feedback').text('Пароли не совпадают');
                $('#password').addClass('is-invalid');
                $('#repeat-password').addClass('is-invalid');
                return;
            } else {
                $('#password').addClass('is-valid');
                $('#repeat-password').addClass('is-valid');
            }

            var formData = $(this).serialize();
            formData = decodeURIComponent(formData);
            //console.log(formData);

            $.ajax({
                url: '/register',
                method: 'POST',
                data: formData,
                success: function(response) {
                    const parsedResponse = JSON.parse(response);
                    //console.log(parsedResponse);

                    // Сбрасываем старые ошибки и классы
                    $('.form-control-feedback').text('');
                    $('input').removeClass('is-invalid is-valid');

                    if (parsedResponse.success === false) {
                        // Отображаем новые ошибки и добавляем классы
                        if (parsedResponse.errors.email) {
                            $('#email-feedback').text(parsedResponse.errors.email);
                            $('#email').addClass('is-invalid');
                        } else {
                            $('#email').addClass('is-valid');
                        }
                        if (parsedResponse.errors.name) {
                            $('#name-feedback').text(parsedResponse.errors.name);
                            $('#name').addClass('is-invalid');
                        } else {
                            $('#name').addClass('is-valid');
                        }
                        if (parsedResponse.errors.password) {
                            $('#password-feedback').text(parsedResponse.errors.password);
                            $('#password').addClass('is-invalid');
                        } else {
                            $('#password').addClass('is-valid');
                        }
                        if (parsedResponse.errors.repeat_password) {
                            $('#repeat-password-feedback').text(parsedResponse.errors.repeat_password);
                            $('#repeat-password').addClass('is-invalid');
                        } else {
                            $('#repeat-password').addClass('is-valid');
                        }
                    } else {
                        // Обработка успешной регистрации
                        //console.log(parsedResponse);
                        window.location.href = '/';
                    }
                },
                error: function(xhr, status, error) {
                    // Сбрасываем старые ошибки и классы
                    $('.form-control-feedback').text('');
                    $('input').removeClass('is-invalid is-valid');

                    $('#email').addClass('is-invalid');
                    $('#name').addClass('is-invalid');
                    $('#password').addClass('is-invalid');
                    $('#repeat-password').addClass('is-invalid');

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