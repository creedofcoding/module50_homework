-- запросы для всех таблиц
-- Отключение проверок внешних ключей
-- PRAGMA foreign_keys = OFF;

-- Удаление всех данных и обнуление автоинкремента для таблицы comments
-- DELETE FROM comments;
-- UPDATE SQLITE_SEQUENCE SET SEQ=0 WHERE NAME='comments';

-- Удаление всех данных и обнуление автоинкремента для таблицы images
-- DELETE FROM images;
-- UPDATE SQLITE_SEQUENCE SET SEQ=0 WHERE NAME='images';

-- Удаление всех данных и обнуление автоинкремента для таблицы users
-- DELETE FROM users;
-- UPDATE SQLITE_SEQUENCE SET SEQ=0 WHERE NAME='users';

-- Включение проверок внешних ключей обратно
-- PRAGMA foreign_keys = ON;

-- Удаление таблиц
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS users;

-- Создание таблиц
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT NOT NULL UNIQUE,
  name TEXT NOT NULL,
  password TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE images (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  filename TEXT NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE comments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  image_id INTEGER NOT NULL,
  comment TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE
);

-- Наполнение таблиц
INSERT INTO 
  users (email, name, password, created_at)
VALUES 
  ('dsa@dsa.ru', 'Ilyas Khairullin', '$2y$10$/0na1LI77gxCwM99XAkdp..20VP3kzT2kavZ42OWOhEBmxKPs1vhm', datetime('now', 'localtime')),
  ('dsadsa@dsa.ru', 'John Doe', '$2y$10$Ppf8jw5LSX3H/GKd83de3u1/mr0k0ma/x5dYyDoqczw1zEWkSzKey', datetime('now', 'localtime')),
  ('dsadsadsa@dsa.ru', 'Jack Morrison', '$2y$10$6i/RXObECeheLU7dQTyC4Ov23XJg.h2t8fu6lieJAMShL/l23O9TC', datetime('now', 'localtime'));

INSERT INTO
  images (user_id, filename, description, created_at)
VALUES
  (1, 'corgi.jpg', 'Корги 1', datetime('now', 'localtime')),
  (1, 'corgi_1.jpg', 'Корги 2', datetime('now', 'localtime')),
  (1, 'corgi_2.jpg', 'Корги 3', datetime('now', 'localtime')),
  (2, 'clouded_leopard.jpg', 'Дымчатый леопард 1', datetime('now', 'localtime')),
  (3, 'clouded_leopard_1.jpeg', 'Дымчатый леопард 2', datetime('now', 'localtime'));

INSERT INTO
  comments (user_id, image_id, comment, created_at)
VALUES
  (1, 1, 'Какой милый корги!', datetime('now', 'localtime')),
  (2, 1, 'Хочу себе такого щенка.', datetime('now', 'localtime')),
  (3, 2, 'Этот корги просто очарователен!', datetime('now', 'localtime')),
  (1, 3, 'Корги всегда такие забавные.', datetime('now', 'localtime')),
  (2, 3, 'Фотография просто супер!', datetime('now', 'localtime')),
  (3, 4, 'Дымчатый леопард выглядит впечатляюще.', datetime('now', 'localtime')),
  (1, 4, 'Удивительное животное!', datetime('now', 'localtime')),
  (2, 5, 'Фантастический кадр с леопардом.', datetime('now', 'localtime')),
  (3, 5, 'Обожаю снимки дикой природы.', datetime('now', 'localtime')),
  (1, 2, 'Этот корги – мой фаворит!', datetime('now', 'localtime'));