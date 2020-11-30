# Kabum - CRUD com Painel de Usuário

CRUD com login, cadastro e painel de usuário em AngularJS e PHP.

## Instruções

Arquivo de configuração de banco de dados da API em
```bash
api/config/Database.php
```

## Documentação

### Autenticação

```bash
Rota: /api/authenticate.php
Método: POST
Parâmetros: cpf, password
```

### Usuário

```bash
# Inserir
Rota: /api/user/create.php
Método: POST
Parâmetros: name, birthdate, password, cpf, rg, phone
```

```bash
# Alterar
Rota: /api/user/update.php?id=ID_USUÁRIO
Método: PUT
Parâmetros: name, birthdate, password, cpf, rg, phone
```

```bash
# Deletar
Rota: /api/user/delete.php?id=ID_USUÁRIO
Método: DELETE
```

```bash
# Mostrar | Mostrar Todos
Rota: /api/user/show.php?id=ID_USUÁRIO | /api/user/show.php
Método: GET
```

### Endereços

```bash
# Inserir
Rota: /api/address/create.php
Método: POST
Parâmetros: user_id, zip, address, number, complement, district, city, state
```

```bash
# Alterar
Rota: /api/address/update.php?id=ID_ENDEREÇO
Método: PUT
Parâmetros: user_id, zip, address, number, complement, district, city, state
```

```bash
# Deletar
Rota: /api/address/delete.php?id=ID_ENDEREÇO
Método: DELETE
```

```bash
# Mostrar | Mostrar Todos
Rota: /api/address/show.php?id=ID_ENDEREÇO | /api/address/show.php
Método: GET
```

```bash
# Mostrar endereços de usuário
Rota: /api/address/user.php?id=ID_USUÁRIO
Método: GET
```
