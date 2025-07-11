[![Logotipo-Avant-Fiscal-Horizontal-2.png](https://i.postimg.cc/JnSjmqgk/Logotipo-Avant-Fiscal-Horizontal-2.png)](https://postimg.cc/R6QWLcvS)

# Desafio Técnico: Sistema de Reserva de Salas de Reunião

## Descrição
Este desafio técnico consiste em desenvolver um sistema de reserva de salas de reunião para uma empresa, utilizando PHP puro e MySQL. O sistema deve permitir que os usuários se registrem e façam login, e que os administradores gerenciem as salas de reunião. Usuários autenticados devem poder visualizar a disponibilidade das salas e fazer reservas.

**Requisitos:**
- **PHP Puro:** Não utilize frameworks de PHP.
- **Conexão com Banco de Dados:** A conexão com o banco de dados será fornecida pela Avant Fiscal, portanto, o candidato deve se preocupar apenas com o código.

**Opcional:**
- **Arquitetura Separada:** Criar o Backend separado do Frontend.
- **Frontend:**
  - Utilizar JavaScript e jQuery para funcionalidades dinâmicas.
  - Para estilização, utilizar Bootstrap ou TailwindCSS.
  - Não utilizar frameworks de frontend. O desenvolvimento do frontend deve ser feito apenas com PHP, HTML, JavaScript e os estilos mencionados acima.

Esta abordagem assegura um código mais enxuto e um melhor entendimento das tecnologias envolvidas, sem a complexidade adicional de frameworks.


## Funcionalidades
Esse desafio precisa obrigatoriamente implementar essas funcionalidades abaixo, fique a vontade para adicionar mais funcionalidades que agregem ao projeto sem mudar o objetivo principal da aplicação.


- **Cadastro de Usuários**
  - Registro de novos usuários.
  - Login de usuários existentes.
  - Diferentes níveis de acesso (administrador e usuário comum).

- **Gerenciamento de Salas**
  - Administradores podem criar, atualizar e excluir salas de reunião.
  - Informações das salas incluem nome, capacidade e localização.

- **Reserva de Salas**
  - Usuários podem visualizar a disponibilidade das salas.
  - Usuários podem fazer reservas especificando sala, data, hora de início e término.

- **Validações**
  - Campos obrigatórios devem ser preenchidos.
  - Verificação de unicidade de email.
  - Garantia de que uma sala não pode ser reservada por mais de um usuário no mesmo horário.

- **Segurança**
  - Proteção contra SQL Injection.
  - Acesso às páginas de gerenciamento restrito a usuários autenticados.

## Estrutura Sugerida do Banco de Dados
(Fique livre para criar sua estrutura com outras tabelas caso ache necessario).

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    access_level ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    user_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Entrega do projeto
- Faça um fork do projeto.
- Crie uma nova branch com o seu nome e sobrenome ex: joao-souza: git checkout -b nome-sobrenome.
- Faça commit das suas alterações: git commit -m 'Minha nova feature'.
- Faça push para a branch: git push origin nome-sobrenome.
- Abra um Pull Request.
