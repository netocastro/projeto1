DROP TABLE IF EXISTS `USUARIOS`;

CREATE TABLE `Usuarios`(
        `id` INTEGER AUTO_INCREMENT,
        `Usu_nome` VARCHAR(100) NOT NULL,
        `Usu_nick` VARCHAR(16) UNIQUE DEFAULT NULL,  
        `Usu_cpf` CHAR(11) NOT NULL,
        `Usu_email` VARCHAR(100) NOT NULL UNIQUE,
        `Usu_senha` CHAR(128) NOT NULL, 
        `Usu_telefone` CHAR(11) DEFAULT NULL,
        `Usu_Endereco` VARCHAR(200) DEFAULT NULL,
        `Usu_anunciante` BOOLEAN DEFAULT NULL,
        `created_at` timestamp default CURRENT_TIMESTAMP,
        `updated_at` timestamp default CURRENT_TIMESTAMP,

        primary key(id)
);

INSERT INTO `Usuarios`(Usu_nome, Usu_cpf, Usu_email, Usu_senha) VALUES
('Joao da Silva', '00000000001', 'joao@joao.com','3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2'),
('Maria dos Santos', '00000000002', 'maria@maria.com','3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2')
