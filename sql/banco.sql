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
CREATE TABLE admin_users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL,
 email VARCHAR(160) NOT NULL UNIQUE,
 senha_hash VARCHAR(255) NOT NULL,
 ativo TINYINT(1) NOT NULL DEFAULT 1,
 criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO admin_users(nome,email,senha_hash,ativo) VALUES
('Secretaria Demo','secretaria@meridiano.test','$2y$12$RbuMWCXp671KPQf/kPU95uc0lOATqtkULzzgtQ2fEiOLuMuG8GDQ2',1);
INSERT INTO turmas(nome,missao,ano) VALUES
('Primeiro ano · Aurora','comum',2027),('Primeiro ano · Zênite','comum',2027),
('Cátedra de Nazaré','galileia',2027),('Caminhos do Sul','brasil',2027),
('Memória da ocupação','guerra',2027),('Vozes do Pacífico','guerra',2027),
('Observatório de Curitiba','futuro',2027),('Cartografia colonial','brasil',2027);

-- 50 cadetes demonstrativos para o dashboard da Secretaria
INSERT INTO cadetes(id,nome,email,ano_formacao,especialidade,turma_id) VALUES
(1, 'Ana Beatriz', 'ana-beatriz.cadete01@meridiano.test', 1, 'Corpo Básico', 1),
(2, 'Bruno Caetano', 'bruno-caetano.cadete02@meridiano.test', 1, 'Corpo Básico', 2),
(3, 'Clara Duarte', 'clara-duarte.cadete03@meridiano.test', 1, 'Corpo Básico', 3),
(4, 'Diego Esteves', 'diego-esteves.cadete04@meridiano.test', 1, 'Corpo Básico', 4),
(5, 'Elisa Farias', 'elisa-farias.cadete05@meridiano.test', 1, 'Corpo Básico', 5),
(6, 'Felipe Gomes', 'felipe-gomes.cadete06@meridiano.test', 1, 'Corpo Básico', 6),
(7, 'Gabriela Horta', 'gabriela-horta.cadete07@meridiano.test', 1, 'Corpo Básico', 7),
(8, 'Heitor Ivo', 'heitor-ivo.cadete08@meridiano.test', 1, 'Corpo Básico', 8),
(9, 'Isabela Jardim', 'isabela-jardim.cadete09@meridiano.test', 1, 'Corpo Básico', 1),
(10, 'Joao Kallas', 'joao-kallas.cadete10@meridiano.test', 1, 'Corpo Básico', 2),
(11, 'Karina Lima', 'karina-lima.cadete11@meridiano.test', 1, 'Corpo Básico', 3),
(12, 'Leonardo Martins', 'leonardo-martins.cadete12@meridiano.test', 1, 'Corpo Básico', 4),
(13, 'Mariana Nogueira', 'mariana-nogueira.cadete13@meridiano.test', 1, 'Corpo Básico', 5),
(14, 'Nicolas Oliveira', 'nicolas-oliveira.cadete14@meridiano.test', 1, 'Corpo Básico', 6),
(15, 'Olivia Pires', 'olivia-pires.cadete15@meridiano.test', 1, 'Corpo Básico', 7),
(16, 'Pedro Queiroz', 'pedro-queiroz.cadete16@meridiano.test', 1, 'Corpo Básico', 8),
(17, 'Rafaela Reis', 'rafaela-reis.cadete17@meridiano.test', 2, 'Saúde e Readaptação Temporal', 1),
(18, 'Samuel Sampaio', 'samuel-sampaio.cadete18@meridiano.test', 2, 'Intendência Temporal', 2),
(19, 'Taina Torres', 'taina-torres.cadete19@meridiano.test', 2, 'Travessia', 3),
(20, 'Vitor Uchoa', 'vitor-uchoa.cadete20@meridiano.test', 2, 'Observação', 4),
(21, 'Alice Valenca', 'alice-valenca.cadete21@meridiano.test', 2, 'Ciências Temporais', 5),
(22, 'Bento Xavier', 'bento-xavier.cadete22@meridiano.test', 2, 'História e Linguagens', 6),
(23, 'Cecilia Yamada', 'cecilia-yamada.cadete23@meridiano.test', 2, 'Saúde e Readaptação Temporal', 7),
(24, 'Davi Zanon', 'davi-zanon.cadete24@meridiano.test', 2, 'Intendência Temporal', 8),
(25, 'Esther Araujo', 'esther-araujo.cadete25@meridiano.test', 2, 'Travessia', 1),
(26, 'Fabio Barros', 'fabio-barros.cadete26@meridiano.test', 2, 'Observação', 2),
(27, 'Giovana Campos', 'giovana-campos.cadete27@meridiano.test', 2, 'Ciências Temporais', 3),
(28, 'Hugo Dantas', 'hugo-dantas.cadete28@meridiano.test', 2, 'História e Linguagens', 4),
(29, 'Iara Escobar', 'iara-escobar.cadete29@meridiano.test', 2, 'Saúde e Readaptação Temporal', 5),
(30, 'Joaquim Freire', 'joaquim-freire.cadete30@meridiano.test', 2, 'Intendência Temporal', 6),
(31, 'Livia Garcia', 'livia-garcia.cadete31@meridiano.test', 3, 'Travessia', 7),
(32, 'Miguel Haddad', 'miguel-haddad.cadete32@meridiano.test', 3, 'Observação', 8),
(33, 'Nina Iglesias', 'nina-iglesias.cadete33@meridiano.test', 3, 'Ciências Temporais', 1),
(34, 'Otavio Junqueira', 'otavio-junqueira.cadete34@meridiano.test', 3, 'História e Linguagens', 2),
(35, 'Paula Kuster', 'paula-kuster.cadete35@meridiano.test', 3, 'Saúde e Readaptação Temporal', 3),
(36, 'Ravi Lopes', 'ravi-lopes.cadete36@meridiano.test', 3, 'Intendência Temporal', 4),
(37, 'Sofia Moraes', 'sofia-moraes.cadete37@meridiano.test', 3, 'Travessia', 5),
(38, 'Theo Neves', 'theo-neves.cadete38@meridiano.test', 3, 'Observação', 6),
(39, 'Ursula Ornelas', 'ursula-ornelas.cadete39@meridiano.test', 3, 'Ciências Temporais', 7),
(40, 'Valentina Prado', 'valentina-prado.cadete40@meridiano.test', 3, 'História e Linguagens', 8),
(41, 'Wesley Ramos', 'wesley-ramos.cadete41@meridiano.test', 3, 'Intendência Temporal', 1),
(42, 'Yasmin Ribeiro', 'yasmin-ribeiro.cadete42@meridiano.test', 3, 'Travessia', 2),
(43, 'Zeca Soares', 'zeca-soares.cadete43@meridiano.test', 4, 'Observação', 3),
(44, 'Agatha Tavares', 'agatha-tavares.cadete44@meridiano.test', 4, 'Ciências Temporais', 4),
(45, 'Bernardo Vaz', 'bernardo-vaz.cadete45@meridiano.test', 4, 'História e Linguagens', 5),
(46, 'Catarina Werneck', 'catarina-werneck.cadete46@meridiano.test', 4, 'Saúde e Readaptação Temporal', 6),
(47, 'Enzo Ximenes', 'enzo-ximenes.cadete47@meridiano.test', 4, 'Intendência Temporal', 7),
(48, 'Flavia Yunes', 'flavia-yunes.cadete48@meridiano.test', 4, 'Travessia', 8),
(49, 'Gustavo Ziegler', 'gustavo-ziegler.cadete49@meridiano.test', 4, 'Observação', 1),
(50, 'Helena Alves', 'helena-alves.cadete50@meridiano.test', 4, 'Ciências Temporais', 2);
