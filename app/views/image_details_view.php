<div class="container mt-4">
    <h1 class="text-center mb-5">Детали изображения</h1>
    <div class="card mx-auto" style="max-width: 600px;">
        <?php if (file_exists(UPLOAD_DIR . htmlspecialchars($data['filename']))): ?>
            <img src="<?= UPLOAD_DIR . htmlspecialchars($data['filename']); ?>" class="card-img-top" alt="Изображение">
        <?php else: ?>
            <p class="text-center mt-3 mb-0 text-danger fw-bold">Нет изображения</p>
        <?php endif; ?>
        
        <div class="card-body">
            <h5 class="card-title pt-0"><?= htmlspecialchars($data['description'] ?: 'Нет описания'); ?></h5>
            <p class="card-text">Опубликовал: <b><?= htmlspecialchars($data['uploader_name']); ?></b></p>
            <p class="card-text">Дата: <?= htmlspecialchars(date('d.m.Y H:i', strtotime($data['created_at']))); ?></p>

            <div class="d-flex gap-2">
                <?php if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $data['user_id']): ?>
                    <form method="POST" action="/delete_image" onsubmit="return confirm('Вы уверены, что хотите удалить это изображение?');">
                        <input type="hidden" name="image_id" value="<?= htmlspecialchars($data['id']); ?>">
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                <?php endif; ?>

                <a href="/gallery" class="btn btn-primary">Назад к галерее</a>
            </div>
        </div>
    </div>

    <?php if ($commentsTableExists): ?>
        <div class="mt-5 mb-5">
            <h3>Комментарии</h3>
            <?php if (isset($_SESSION['user'])): ?>
                <form method="POST" action="/add_comment">
                    <input type="hidden" name="image_id" value="<?= htmlspecialchars($data['id']); ?>">
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" placeholder="Напишите комментарий..." rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Добавить комментарий</button>
                </form>
            <?php endif; ?>

            <div class="comments-section mt-4">
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-block mb-4 p-3 border rounded">
                            <div class="comment-header d-flex justify-content-between">
                                <span><b><?= htmlspecialchars($comment['commenter_name']); ?></b></span>
                                <span class="text-muted"><?= htmlspecialchars(date('d.m.Y H:i', strtotime($comment['created_at']))); ?></span>
                            </div>
                            <div class="comment-body mt-2">
                                <p class="mb-0"><?= nl2br(htmlspecialchars($comment['comment'])); ?></p>
                            </div>

                            <?php if (isset($_SESSION['user']['id']) && $_SESSION['user']['name'] == $comment['commenter_name']): ?>
                                <form method="POST" action="/delete_comment" class="d-inline mt" onsubmit="return confirm('Вы уверены, что хотите удалить этот комментарий?');">
                                    <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm mt-3">Удалить</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Нет доступных комментариев</p>
                <?php endif; ?>
            </div>

            <?php if (!isset($_SESSION['user'])): ?>
                <div class="alert alert-info mt-4">
                    Если хотите оставить комментарий — <a href="/login" class="alert-link">войдите</a> или <a href="/register" class="alert-link">зарегистрируйтесь</a>.
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4">
            Ошибка сервера! Комментарии недоступны!
        </div>
    <?php endif; ?>
</div>