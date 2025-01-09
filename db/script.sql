-- Create the database 
CREATE DATABASE IF NOT EXISTS certificados;

-- Use the database
USE certificados;

-- Create the cursos table
CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_curso VARCHAR(255) NOT NULL,
    professor VARCHAR(255) NOT NULL,
    carga_horaria VARCHAR(50) NOT NULL,
    template_path_frente VARCHAR(255) NOT NULL,
    template_path_verso VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the certificados_gerados table
CREATE TABLE certificados_gerados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    nome_aluno VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    numeracao VARCHAR(50) NOT NULL,
    data_emissao DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
);

-- Criar a tabela usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identificador único
    nome VARCHAR(255) NOT NULL,        -- Nome completo do usuário
    email VARCHAR(255) NOT NULL UNIQUE, -- E-mail do usuário, único para login
    senha VARCHAR(255) NOT NULL,       -- Senha armazenada de forma segura (hash)
    perfil ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario', -- Define o tipo de acesso
    status TINYINT(1) NOT NULL DEFAULT 1, -- Indica se o usuário está ativo (1) ou inativo (0)
    ultimo_acesso DATETIME DEFAULT NULL, -- Data e hora do último acesso do usuário
    contador_visitas INT DEFAULT 0,      -- Número de vezes que o usuário acessou o sistema
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data de criação
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Última atualização
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir o usuário admin
INSERT INTO usuarios (nome, email, senha, perfil, status, ultimo_acesso, contador_visitas) 
VALUES (
    'admin', 
    'alesauer@gmail.com', 
    SHA2('admin', 256), -- Senha armazenada com hash SHA-256
    'admin', 
    1, 
    NULL, 
    0
);
