# API RestFull com CodeIgniter 3 e MySQL

## Índice
- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Arquitetura e Domínio](#arquitetura-e-domínio)
- [Como Usar](#como-usar)
- [Endpoints](#endpoints)
  - [Criar um usuário](#criar-um-usuário)
  - [Listar usuários](#listar-usuários)
  - [Consultar usuário](#consultar-usuário)
  - [Editar usuário](#editar-usuário)
  - [Deletar usuário](#deletar-usuário)
- [Autenticação](#autenticação)
- [Regras de Negócio](#regras-de-negócio)

---

## Sobre o Projeto
Esta é uma API RestFull desenvolvida em **CodeIgniter 3** e **MySQL**, com objetivo de demonstrar o conceito de **Domain Driven Design**. Aqui, temos o domínio de `usuário`, responsável por gerenciar as operações de CRUD (Create, Read, Update, Delete) de usuários no sistema.

---

## Tecnologias
- **[CodeIgniter 3](https://codeigniter.com/)**
- **MySQL**
- **Docker / Docker Compose** (para orquestração de containers)

---

## Requisitos
- **Docker** instalado
- **Docker Compose** instalado

---

## Arquitetura e Domínio
A aplicação foi estruturada com base em **DDD (Domain Driven Design)**. No contexto do domínio de `usuário`, temos:
- Entidade: `Usuário`
  - Propriedades: `id`, `nome`, `email`, `senha`
- Serviços do domínio:
  - Criar um usuário
  - Listar usuários
  - Consultar um usuário
  - Editar um usuário
  - Deletar um usuário

---

## Como Usar

1. **Clonar o repositório**:
  ```bash
    git clone https://github.com/seu-usuario/seu-projeto.git
   ```

2 **Acessar o diretório do projeto**:
```bash
  cd seu-projeto
```

3 **Subir os containers com Docker Compose:**
 ```bash
  docker-compose up -d
 ```
**O docker-compose cria:**
  - Um container para a API (CodeIgniter 3)
  - Um container para o banco de dados MySQL

4 **Verificar as migrations:**
  
O Dockerfile contém um script entrypoint.sh que verifica a disponibilidade do banco de dados e executa as migrations automaticamente ao subir os containers.

5 **Acessar a aplicação:**
  A API estará disponível em:
  ```bash
  http://localhost:8080/index.php
  ```
## Endpoints

**Criar um usuário**
- Endpoint: POST /api/usuarios
- Body:
 ```json
 {
  "nome": "Henrique Clementino",
  "email": "email@gmail.com",
  "senha": "G23xssd15$"
}
  ```

- Descrição: Cria um novo usuário com os dados fornecidos.

**Listar usuários**
- Endpoint: GET /api/usuarios
- Descrição: Retorna a lista de todos os usuários cadastrados.

**Consultar usuário**
- Endpoint: GET /api/usuarios/{id}
- Descrição: Retorna os dados de um usuário específico, com base no id.

**Editar usuário**
- Endpoint: PUT /api/usuarios/{id}
- Body:
 ```json
 {
  "nome": "Henrique Clementino"
}
  ```

- Descrição: Atualiza os dados do usuário referente ao id informado.

**Deletar  usuário**
- Endpoint: DELETE /api/usuarios/{id}
- Descrição: Exclui o usuário referente ao id informado.

## Autenticação
- A autenticação está configurada como Basic Auth.
- É necessário fornecer as credenciais corretas ao acessar os endpoints protegidos.
- username: usuario
- password: senha

## Regras de Negócio
- Cada usuário só pode ter um único e-mail (e-mail único por usuário).
- Para criar um novo usuário, são obrigatórios os campos nome, email e senha.


