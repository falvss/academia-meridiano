-- Migração v3: autenticação administrativa por e-mail e senha
USE academia_meridiano;
CREATE TABLE IF NOT EXISTS admin_users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL,
 email VARCHAR(160) NOT NULL UNIQUE,
 senha_hash VARCHAR(255) NOT NULL,
 ativo TINYINT(1) NOT NULL DEFAULT 1,
 criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT IGNORE INTO admin_users(nome,email,senha_hash,ativo) VALUES
('Secretaria Demo','secretaria@meridiano.test','$2y$12$RbuMWCXp671KPQf/kPU95uc0lOATqtkULzzgtQ2fEiOLuMuG8GDQ2',1);
