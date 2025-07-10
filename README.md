# Sistema de Reservas

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/6ae7a1c5-983d-4f4e-98b8-285c447379c9" />

## 📋 Sobre o Projeto

Este é um **desafio técnico** desenvolvido para a **Avant Fiscal**, consistindo em um sistema completo de reserva de salas de reunião desenvolvido em **PHP puro** e **MySQL**.

### 🎯 Funcionalidades Implementadas

- ✅ **Autenticação**: Cadastro, login e diferentes níveis de acesso (admin/user)
- ✅ **Gerenciamento de Salas**: CRUD completo para administradores
- ✅ **Sistema de Reservas**: Visualização de disponibilidade e criação de reservas
- ✅ **Validações**: Campos obrigatórios, unicidade de email, conflitos de horário
- ✅ **Segurança**: Proteção SQL Injection, controle de acesso
- ✅ **Interface Moderna**: Frontend responsivo com Tailwind CSS e JavaScript


## 🚀 Como Rodar

### Opção 1: Testar Online
Acesse: https://avant.gsmatheus.com/

### Opção 2: Docker (Mais Fácil)
```bash
cd docker
chmod +x start.sh
./start.sh
```
Acesse: http://localhost:8050

### Opção 3: PHP Built-in Server
```bash
# 1. Configure o banco MySQL
mysql -u root -p < backend/database/init.sql

# 2. Configure as credenciais em backend/database/database.php
# (host, dbname, username, password, port)

# 3. Inicie o servidor
php -S localhost:8080

# 4. Acesse
http://localhost:8080
```

## 👤 Login Padrão
- **Email**: admin@sistema.com
- **Senha**: password

---

**Desenvolvido como desafio técnico para [Avant Fiscal](https://avantfiscal.com.br/)**
