CREATE DATABASE IF NOT EXISTS academia_meridiano CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE academia_meridiano;
CREATE TABLE turmas (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(100) NOT NULL UNIQUE,
 missao ENUM('comum','galileia','brasil','guerra','futuro') NOT NULL, ano SMALLINT UNSIGNED NOT NULL
) ENGINE=InnoDB;
CREATE TABLE cadetes (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(100) NOT NULL, email VARCHAR(160) NOT NULL UNIQUE,
 ano_formacao TINYINT UNSIGNED NULL, especialidade VARCHAR(70) NULL,
 turma_id INT UNSIGNED NOT NULL, FOREIGN KEY(turma_id) REFERENCES turmas(id) ON DELETE RESTRICT
) ENGINE=InnoDB;
CREATE TABLE inscricoes (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(100) NOT NULL, email VARCHAR(160) NOT NULL,
 edicao SMALLINT UNSIGNED NOT NULL DEFAULT 2, gerais TINYINT UNSIGNED NULL, especificos TINYINT UNSIGNED NULL, corrida_m SMALLINT UNSIGNED NULL, percurso_s SMALLINT UNSIGNED NULL, avaliador VARCHAR(100) NULL, data_taf DATE NULL,
 interesse VARCHAR(20) NOT NULL, nota TINYINT UNSIGNED NOT NULL, respostas JSON NULL, criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY email_edicao(email,edicao)
) ENGINE=InnoDB;
INSERT INTO turmas(nome,missao,ano) VALUES
('Primeiro ano · Aurora','comum',2027),('Primeiro ano · Zênite','comum',2027),
('Cátedra de Nazaré','galileia',2027),('Caminhos do Sul','brasil',2027),
('Memória da ocupação','guerra',2027),('Vozes do Pacífico','guerra',2027),
('Observatório de Curitiba','futuro',2027),('Cartografia colonial','brasil',2027);
