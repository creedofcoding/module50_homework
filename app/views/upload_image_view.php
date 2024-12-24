<div class="row justify-content-center mt-5">
    <div class="col-5">
        <h1 class="text-center mb-5">Загрузить изображение</h1>

        <div class="upload-form_container">
            <form id="upload-form" action="/upload_images" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="image" class="form-label">Выберите изображение. Поддерживаемые форматы: <b>jpg, jpeg, png, gif</b></label>
                    <input id='image' class="form-control" type='file' name='file[]' multiple required>
                    <div class="form-control-feedback" id="image-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Описание (опционально)</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Загрузить</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#upload-form').on('submit', function(e) {
            e.preventDefault(); // Предотвращаем стандартную отправку формы

            const image_feedback = document.getElementById('image-feedback');
            image_feedback.innerHTML = ''; // Очищаем старые сообщения

            // Получаем данные формы
            const formData = new FormData(this);
            //console.log(formData);

            // AJAX запрос
            $.ajax({
                url: '/upload_images', // URL для загрузки
                method: 'POST',
                data: formData,
                contentType: false, // Для отправки файла
                processData: false, // Отключаем преобразование данных
                success: function(response) {
                    //console.log(response);

                    if (response.success) {
                        //image_feedback.innerHTML = '<span style="color: green;">Файлы успешно загружены!</span>';
                        window.location.href = '/gallery';
                    }
                },
                error: function(xhr, status, error) {
                    // Считываем ответ от сервера
                    const result = JSON.parse(xhr.responseText);
                    //console.log(result);

                    if (xhr.status === 500) {
                        // Если произошла ошибка сервера
                        Swal.fire({
                            icon: 'error',
                            title: 'Произошла ошибка',
                            text: `Ошибка: ${error}.`,
                        });
                    } else if (xhr.status === 400 && result.error) {
                        // Выводим ошибку в image_feedback
                        image_feedback.innerHTML = `${result.error}`;
                    } else {
                        // Если произошла другая ошибка
                        Swal.fire({
                            icon: 'error',
                            title: 'Произошла ошибка',
                            text: `Ошибка: ${error}. Попробуйте еще раз.`,
                        });

                        // Выводим общую ошибку в image_feedback
                        //image_feedback.innerHTML = `<span style="color: red;">${error}</span>`;
                    }
                },
            });
        });
    });
</script>