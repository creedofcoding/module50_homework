<div class="container mt-4">
    <h1 class="text-center mb-5">Галерея изображений</h1>
    <div class="row">
        <?php if (!empty($data)): ?>
            <?php if (isset($_SESSION['user'])): ?>
                <div class="col-md-4 mb-4">
                    <a href="/upload_images" class="d-block text-center">
                        <img src="/assets/img/add_image.jpg" class="img-fluid" alt="Добавить изображение">
                    </a>
                </div>
            <?php endif; ?>

            <?php foreach ($data as $image): ?>
                <div class="col-md-4 mb-2">
                    <div class="card">
                        <?php if (file_exists(UPLOAD_DIR . $image['filename'])): ?>
                            <a href="/view_image?id=<?= htmlspecialchars($image['id']); ?>">
                                <img src="<?= UPLOAD_DIR . htmlspecialchars($image['filename']); ?>" class="card-img-top" alt="Изображение">
                            </a>
                        <?php else: ?>
                            <p class="text-center mt-3 mb-0 text-danger fw-bold">Нет изображения</p>
                        <?php endif; ?>
                        <div class="card-body">
                            <p class="card-text">
                                <?php if (!empty($image['description'])): ?>
                                    <?= htmlspecialchars($image['description']); ?>
                                <?php else: ?>
                                    Нет описания
                                <?php endif; ?>
                            </p>
                            <p class="card-text">Опубликовал: <b><?= htmlspecialchars($image['name']); ?></b></p>
                            <p class="card-text">Дата: <?= htmlspecialchars(date('d.m.Y H:i', strtotime($image['created_at']))); ?></p>

                            <?php if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $image['user_id']): ?>
                                <form method="POST" action="/delete_image" onsubmit="return confirm('Вы уверены, что хотите удалить это изображение?');">
                                    <input type="hidden" name="image_id" value="<?= htmlspecialchars($image['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Нет доступных изображений</p>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user'])): ?>
            <div class="alert alert-info">
                Если хотите загрузить изображение — <a href="/login" class="alert-link">войдите</a> или <a href="/register" class="alert-link">зарегистрируйтесь</a>.
            </div>
        <?php endif; ?>
    </div>
</div>