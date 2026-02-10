-- database
CREATE DATABASE IF NOT EXISTS laravelprod
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- user
CREATE USER IF NOT EXISTS 'appuser'@'%' IDENTIFIED BY 'USER_PASSWORD';

-- can only modify this database
GRANT
  SELECT,
  INSERT,
  UPDATE,
  DELETE,
  CREATE,
  ALTER,
  INDEX,
  DROP
ON laravelprod.* TO 'appuser'@'%';

FLUSH PRIVILEGES;
