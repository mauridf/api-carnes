
# API RESTful para Parcelas de Carnê

Esta aplicação é uma API RESTful desenvolvida em Laravel (PHP) para gerar e apresentar as parcelas de uma geração de carnê. 

## Funcionalidades da API

1. **Criação de um Carnê**
   - Endpoint: `POST /api/carne`
   - Parâmetros:
     - `valor_total` (float): O valor total do carnê.
     - `qtd_parcelas` (int): A quantidade de parcelas.
     - `data_primeiro_vencimento` (string, formato `YYYY-MM-DD`): A data do primeiro vencimento.
     - `periodicidade` (string, valores possíveis: "mensal", "semanal"): A periodicidade das parcelas.
     - `valor_entrada` (float, opcional): O valor da entrada.
   - Resposta: 
     - JSON contendo o valor total, o valor de entrada (se houver) e uma lista de parcelas.

2. **Recuperação de Parcelas**
   - Endpoint: `GET /api/carne/{id}/parcelas`
   - Parâmetros:
     - `id` (int): O identificador do carnê.
   - Resposta:
     - JSON contendo as parcelas associadas ao carnê.

## Cenários Obrigatórios

### 1. Divisão de R$ 100,00 em 12 Parcelas
**Entrada:**
```json
{
    "valor_total": 100.00,
    "qtd_parcelas": 12,
    "data_primeiro_vencimento": "2024-08-01",
    "periodicidade": "mensal"
}
```
**Saída esperada:** O somatório das parcelas deve ser igual a 100.00.

### 2. Divisão de R$ 0,30 em 2 Parcelas com Entrada de R$ 0,10
**Entrada:**
```json
{
    "valor_total": 0.30,
    "qtd_parcelas": 2,
    "data_primeiro_vencimento": "2024-08-01",
    "periodicidade": "semanal",
    "valor_entrada": 0.10
}
```
**Saída esperada:** O somatório das parcelas deve ser igual a 0.30, com a entrada considerada como uma parcela.

## Requisitos

- **PHP** >= 7.4
- **Composer**
- **PostgreSQL**
- **Laravel Framework** >= 9.x

## Instalação e Configuração

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/nome-do-repositorio.git
   cd nome-do-repositorio
   ```

2. **Instale as dependências do projeto:**
   ```bash
   composer install
   ```

3. **Crie o arquivo `.env`:**
   - Copie o arquivo `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```

4. **Configure o arquivo `.env`:**
   - Configure as variáveis de ambiente para conexão com o banco de dados PostgreSQL:
     ```env
     DB_CONNECTION=pgsql
     DB_HOST=127.0.0.1
     DB_PORT=5432
     DB_DATABASE=nome_do_banco
     DB_USERNAME=seu_usuario
     DB_PASSWORD=sua_senha
     ```

5. **Gere a chave da aplicação:**
   ```bash
   php artisan key:generate
   ```

6. **Execute as migrations:**
   ```bash
   php artisan migrate
   ```

## Executando o Servidor

Para iniciar o servidor de desenvolvimento, execute:

```bash
php artisan serve
```

A aplicação estará disponível em `http://127.0.0.1:8000`.

## Testando a API

### Criar um Carnê

- **Método**: `POST`
- **URL**: `http://127.0.0.1:8000/api/carne`
- **Corpo da Requisição** (JSON):
  ```json
  {
      "valor_total": 100.00,
      "qtd_parcelas": 12,
      "data_primeiro_vencimento": "2024-08-01",
      "periodicidade": "mensal"
  }
  ```

### Recuperar Parcelas

- **Método**: `GET`
- **URL**: `http://127.0.0.1:8000/api/carne/{id}/parcelas`
  - Substitua `{id}` pelo ID do carnê criado.

## Documentação da API

### Endpoints

1. `POST /api/carne` - Cria um novo carnê com parcelas.
2. `GET /api/carne/{id}/parcelas` - Recupera as parcelas de um carnê específico.

## Contribuições

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou pull requests.

## Licença

Este projeto está licenciado sob a licença MIT.
