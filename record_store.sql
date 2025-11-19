-- Create database
CREATE DATABASE IF NOT EXISTS record_store
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE record_store;

-- Table: formats (dropdown source)
DROP TABLE IF EXISTS formats;
CREATE TABLE formats (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO formats (name) VALUES
  ('cd'),
  ('8 track'),
  ('mp4'),
  ('45'),
  ('72');

-- Table: genres (for optional JOIN flair)
DROP TABLE IF EXISTS genres;
CREATE TABLE genres (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO genres (name) VALUES
  ('Rock'),
  ('Pop'),
  ('Jazz'),
  ('Hip-Hop'),
  ('Classical');

-- Table: records (only table your form will write to)
DROP TABLE IF EXISTS records;
CREATE TABLE records (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  title     VARCHAR(150) NOT NULL,
  artist    VARCHAR(120) NOT NULL,
  price     DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  format_id INT NOT NULL,
  genre_id  INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_records_format
    FOREIGN KEY (format_id) REFERENCES formats(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_records_genre
    FOREIGN KEY (genre_id) REFERENCES genres(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- Seed a few sample records
INSERT INTO records (title, artist, price, format_id, genre_id) VALUES
  ('Kind of Blue', 'Miles Davis', 14.99,
    (SELECT id FROM formats WHERE name='cd'),
    (SELECT id FROM genres  WHERE name='Jazz')),
  ('Abbey Road', 'The Beatles', 19.99,
    (SELECT id FROM formats WHERE name='45'),
    (SELECT id FROM genres  WHERE name='Rock')),
  ('1989 (Taylor\'s Version)', 'Taylor Swift', 12.49,
    (SELECT id FROM formats WHERE name='mp4'),
    (SELECT id FROM genres  WHERE name='Pop'));