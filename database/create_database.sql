-- Inserção de um usuário de teste (senha: 123456)
INSERT INTO usuarios (nome, email, senha) 
VALUES ('Fernando Teste', 'fernando@example.com', SHA1('123456'));
