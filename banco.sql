SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS produto (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(150) NOT NULL,
    descricao TEXT,
    valor     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    foto      VARCHAR(255),
    destaque  TINYINT(1) DEFAULT 0,
    ativo     TINYINT(1) DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admin (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    usuario   VARCHAR(60) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS configuracao (
    chave VARCHAR(80) PRIMARY KEY,
    valor TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

INSERT IGNORE INTO admin (usuario, senha_hash) VALUES
('', '');

INSERT IGNORE INTO configuracao (chave, valor) VALUES
('whatsapp', '5511999999999'),
('nome_loja', 'Minha Loja'),
('email', 'contato@minhaloja.com');

INSERT INTO produto (nome, descricao, valor, destaque) VALUES
('Produto Exemplo 1', 'Descricao detalhada do produto 1. Qualidade premium e acabamento refinado.', 299.90, 1),
('Produto Exemplo 2', 'Descricao detalhada do produto 2. Ideal para uso diario e ocasioes especiais.', 149.90, 1),
('Produto Exemplo 3', 'Descricao detalhada do produto 3. Design moderno e funcional.', 399.90, 0),
('Produto Exemplo 4', 'Descricao detalhada do produto 4. Conforto e durabilidade garantidos.', 89.90, 0);
