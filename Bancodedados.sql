CREATE DATABASE madeira_de_lei;
USE madeira_de_lei;

CREATE TABLE clientes (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    cnpj VARCHAR(18) NOT NULL UNIQUE,
    razao_social VARCHAR(100) NOT NULL,
    endereco_cobranca VARCHAR(200) NOT NULL,
    endereco_correspondencia VARCHAR(200),
    endereco_entrega VARCHAR(200),
    telefones VARCHAR(100),
    pessoa_contato VARCHAR(100),
    ramo_atividade VARCHAR(100),
    data_cadastro DATE NOT NULL
);

CREATE TABLE produtos (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cor VARCHAR(50),
    dimensoes VARCHAR(50),
    peso DECIMAL(10, 2),
    preco DECIMAL(10, 2) NOT NULL,
    tempo_fabricacao INT
);

CREATE TABLE componentes (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    quantidade_estoque INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    unidade_estoque VARCHAR(50) NOT NULL,
    tipo ENUM('Matéria-Prima', 'Material Diverso', 'Máquina', 'Ferramenta') NOT NULL,
    tempo_vida INT,
    data_compra DATE,
    data_fim_garantia DATE
);

CREATE TABLE fornecedores (
    cnpj VARCHAR(18) PRIMARY KEY,
    razao_social VARCHAR(100) NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    telefones VARCHAR(100),
    pessoa_contato VARCHAR(100)
);

CREATE TABLE encomendas (
    numero INT AUTO_INCREMENT PRIMARY KEY,
    data_inclusao DATE NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    valor_desconto DECIMAL(10, 2),
    valor_liquido DECIMAL(10, 2) NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    quantidade_parcelas INT,
    codigo_cliente INT,
    FOREIGN KEY (codigo_cliente) REFERENCES clientes(codigo) ON DELETE CASCADE
);

CREATE TABLE itens_encomenda (
    numero_encomenda INT,
    codigo_produto INT,
    quantidade INT NOT NULL,
    data_necessidade DATE NOT NULL,
    PRIMARY KEY (numero_encomenda, codigo_produto),
    FOREIGN KEY (numero_encomenda) REFERENCES encomendas(numero) ON DELETE CASCADE,
    FOREIGN KEY (codigo_produto) REFERENCES produtos(codigo) ON DELETE CASCADE
);

CREATE TABLE mao_de_obra (
    matricula INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    endereco VARCHAR(200),
    telefones VARCHAR(100),
    cargo VARCHAR(100) NOT NULL,
    salario DECIMAL(10, 2) NOT NULL,
    data_admissao DATE NOT NULL,
    qualificacoes TEXT,
    matricula_gerente INT,
    FOREIGN KEY (matricula_gerente) REFERENCES mao_de_obra(matricula)
);

CREATE TABLE manutencoes (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    codigo_maquina INT NOT NULL,
    cnpj_empresa VARCHAR(18) NOT NULL,
    data_manutencao DATE NOT NULL,
    descricao TEXT,
    FOREIGN KEY (codigo_maquina) REFERENCES componentes(codigo) ON DELETE CASCADE,
    FOREIGN KEY (cnpj_empresa) REFERENCES fornecedores(cnpj) ON DELETE CASCADE
);

CREATE TABLE produtos_componentes (
    codigo_produto INT,
    codigo_componente INT,
    quantidade DECIMAL(10, 2) NOT NULL,
    unidade_medida VARCHAR(50),
    tempo_uso INT,
    PRIMARY KEY (codigo_produto, codigo_componente),
    FOREIGN KEY (codigo_produto) REFERENCES produtos(codigo) ON DELETE CASCADE,
    FOREIGN KEY (codigo_componente) REFERENCES componentes(codigo) ON DELETE CASCADE
);

CREATE TABLE componentes_fornecedores (
    codigo_componente INT,
    cnpj_fornecedor VARCHAR(18),
    PRIMARY KEY (codigo_componente, cnpj_fornecedor),
    FOREIGN KEY (codigo_componente) REFERENCES componentes(codigo) ON DELETE CASCADE,
    FOREIGN KEY (cnpj_fornecedor) REFERENCES fornecedores(cnpj) ON DELETE CASCADE
);

CREATE TABLE encomendas_mao_de_obra (
    numero_encomenda INT,
    matricula_empregado INT,
    horas_trabalhadas DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (numero_encomenda, matricula_empregado),
    FOREIGN KEY (numero_encomenda) REFERENCES encomendas(numero) ON DELETE CASCADE,
    FOREIGN KEY (matricula_empregado) REFERENCES mao_de_obra(matricula) ON DELETE CASCADE
);

ALTER TABLE produtos ADD COLUMN desenho VARCHAR(255);
