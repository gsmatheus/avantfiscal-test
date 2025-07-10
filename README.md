# Sistema de Reservas

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/6ae7a1c5-983d-4f4e-98b8-285c447379c9" />


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
