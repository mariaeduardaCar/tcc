create database tech_connect;
USE tech_connect;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,         -- ID único para cada usuário
  nome VARCHAR(50) NOT NULL,                 -- Campo para o primeiro nome do usuário
  sobrenome VARCHAR(50) NOT NULL,            -- Campo para o sobrenome do usuário
  email VARCHAR(100) NOT NULL UNIQUE,        -- Campo para o e-mail, que deve ser único
  senha VARCHAR(255) NOT NULL,               -- Campo para a senha (armazenada com hash)
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Data de criação do cadastro
);


CREATE TABLE sessoes (
  id INT AUTO_INCREMENT PRIMARY KEY,              -- ID único para cada sessão
  usuario_id INT NOT NULL,                        -- Relaciona a sessão com um usuário específico
  token VARCHAR(100) NOT NULL UNIQUE,             -- Token de sessão exclusivo para autenticação
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação da sessão
  data_expiracao TIMESTAMP                        -- Data de expiração do token, opcional
);

ALTER TABLE sessoes
ADD FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE;

SELECT * FROM usuarios;

CREATE TABLE pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    valor DECIMAL(10, 2),
    status VARCHAR(20),
    data_pagamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    metodo_pagamento VARCHAR(50),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

ALTER TABLE pagamentos MODIFY COLUMN status ENUM('pendente', 'concluído', 'falhou', 'reembolsado') NOT NULL DEFAULT 'pendente';

DELETE FROM pagamentos where id= 5;


  