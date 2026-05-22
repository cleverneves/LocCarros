# Vayro — API de Locação de Veículos

API REST para gerenciamento de locação de veículos, construída com **Laravel 9** e autenticada via **JWT**.

## Stack

- **PHP 8.1** (Alpine)
- **Laravel 9**
- **PostgreSQL 16**
- **Redis**
- **Nginx**
- **Docker / Docker Compose**

## Serviços Docker

| Container | Descrição | Porta |
|-----------|-----------|-------|
| `vayro-app` | Aplicação Laravel (PHP-FPM) | — |
| `vayro-nginx` | Servidor web Nginx | `8989` |
| `vayro-pgsql` | Banco de dados PostgreSQL 16 | `5432` |
| `vayro-redis` | Cache e filas Redis | — |
| `vayro-queue` | Worker de filas Laravel | — |

## Instalação

### 1. Clone o repositório e copie o `.env`

```bash
cp .env.example .env
```

### 2. Suba os containers

```bash
docker-compose up -d --build
```

> O serviço `app` aguarda o PostgreSQL estar saudável antes de iniciar.

### 3. Instale as dependências PHP

```bash
docker-compose exec app composer install
```

### 4. Gere as chaves da aplicação

```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan jwt:secret
```

### 5. Execute as migrations

```bash
docker-compose exec app php artisan migrate
```

### 6. (Opcional) Crie o link de armazenamento público

```bash
docker-compose exec app php artisan storage:link
```

A API estará disponível em **[http://localhost:8989](http://localhost:8989)**.

---

## Autenticação

A API usa **JWT** como mecanismo de autenticação. Todas as rotas sob `/api/v1` exigem o token no header:

```
Authorization: Bearer {token}
```

### Endpoints de autenticação

| Método | Rota | Autenticação | Descrição |
|--------|------|--------------|-----------|
| `POST` | `/api/v1/login` | Não | Obtém o token JWT |
| `POST` | `/api/v1/me` | Sim | Retorna o usuário autenticado |
| `POST` | `/api/v1/refresh` | Sim | Renova o token |
| `POST` | `/api/v1/logout` | Sim | Invalida o token |

---

## Endpoints da API

Todas as rotas abaixo exigem autenticação JWT.

### Marcas — `/api/v1/marca`

| Método | Rota | Descrição |
|--------|------|-----------|
| `GET` | `/api/v1/marca` | Lista todas as marcas |
| `POST` | `/api/v1/marca` | Cadastra uma marca (aceita imagem PNG) |
| `GET` | `/api/v1/marca/{id}` | Exibe uma marca com seus modelos |
| `PUT/PATCH` | `/api/v1/marca/{id}` | Atualiza uma marca |
| `DELETE` | `/api/v1/marca/{id}` | Remove uma marca |

### Modelos — `/api/v1/modelo`

| Método | Rota | Descrição |
|--------|------|-----------|
| `GET` | `/api/v1/modelo` | Lista todos os modelos |
| `POST` | `/api/v1/modelo` | Cadastra um modelo (aceita imagem PNG/JPEG) |
| `GET` | `/api/v1/modelo/{id}` | Exibe um modelo com sua marca |
| `PUT/PATCH` | `/api/v1/modelo/{id}` | Atualiza um modelo |
| `DELETE` | `/api/v1/modelo/{id}` | Remove um modelo |

### Carros — `/api/v1/carro`

| Método | Rota | Descrição |
|--------|------|-----------|
| `GET` | `/api/v1/carro` | Lista todos os carros |
| `POST` | `/api/v1/carro` | Cadastra um carro |
| `GET` | `/api/v1/carro/{id}` | Exibe um carro com seu modelo |
| `PUT/PATCH` | `/api/v1/carro/{id}` | Atualiza um carro |
| `DELETE` | `/api/v1/carro/{id}` | Remove um carro |

### Clientes — `/api/v1/cliente`

| Método | Rota | Descrição |
|--------|------|-----------|
| `GET` | `/api/v1/cliente` | Lista todos os clientes |
| `POST` | `/api/v1/cliente` | Cadastra um cliente |
| `GET` | `/api/v1/cliente/{id}` | Exibe um cliente |
| `PUT/PATCH` | `/api/v1/cliente/{id}` | Atualiza um cliente |
| `DELETE` | `/api/v1/cliente/{id}` | Remove um cliente |

### Locações — `/api/v1/locacao`

| Método | Rota | Descrição |
|--------|------|-----------|
| `GET` | `/api/v1/locacao` | Lista todas as locações |
| `POST` | `/api/v1/locacao` | Registra uma locação |
| `GET` | `/api/v1/locacao/{id}` | Exibe uma locação |
| `PUT/PATCH` | `/api/v1/locacao/{id}` | Atualiza uma locação |
| `DELETE` | `/api/v1/locacao/{id}` | Remove uma locação |

---

## Filtros e seleção de atributos

Os endpoints de listagem (`GET`) aceitam query strings para personalizar a resposta:

| Parâmetro | Exemplo | Descrição |
|-----------|---------|-----------|
| `filtro` | `?filtro=nome:like:%Ford%` | Filtra registros (`coluna:operador:valor`) |
| `atributos` | `?atributos=id,nome` | Seleciona apenas as colunas informadas |
| `atributos_modelos` | `?atributos_modelos=id,nome` | Limita colunas do relacionamento (marcas) |
| `atributos_marca` | `?atributos_marca=id,nome` | Limita colunas do relacionamento (modelos) |
| `atributos_modelo` | `?atributos_modelo=id,nome` | Limita colunas do relacionamento (carros) |

---

## Comandos úteis

```bash
# Acessar o container da aplicação
docker-compose exec app sh

# Ver logs da aplicação
docker-compose logs -f app

# Parar os containers
docker-compose down

# Parar e remover os volumes
docker-compose down -v
```
