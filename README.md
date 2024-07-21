## People Management System

### Descrição

Este é um sistema de gerenciamento de pessoas que permite criar, editar, excluir e visualizar informações de pessoas, bem como gerenciar seus endereços. O projeto é construído utilizando Laravel no backend e Vue.js com Vuetify no frontend. O sistema também faz uso de Docker para facilitar a configuração e execução do ambiente de desenvolvimento.

### Funcionalidades

- Adicionar novas pessoas
- Pesquisar pessoas por nome, CPF, ID ou localização (estado, cidade, bairro)
- Visualizar detalhes de uma pessoa
- Editar informações de uma pessoa
- Adicionar, editar e excluir endereços de pessoas
- Visualizar o histórico de endereços, incluindo endereços excluídos

### Requisitos

- Docker
- Docker Compose
- Laravel Sail

### Configuração do Ambiente

#### Passo 1: Clonar o Repositório

```bash
git clone https://github.com/seu-usuario/people-management-system.git
cd people-management-system
```

#### Passo 2: Configurar as Variáveis de Ambiente

Crie um arquivo `.env` na raiz do projeto e configure as variáveis de ambiente necessárias. Você pode copiar o conteúdo do arquivo `.env.example` para o arquivo `.env`.

```bash
cp .env.example .env
```

#### Passo 3: Inicializar o Docker com Laravel Sail

Suba os containers Docker utilizando o Laravel Sail.

```bash
./vendor/bin/sail up -d
```

#### Passo 4: Instalar Dependências do Backend

Execute os seguintes comandos para instalar as dependências do Laravel e configurar o banco de dados:

```bash
./vendor/bin/sail composer install
./vendor/bin/sail artisan migrate
```

#### Passo 5: Instalar Dependências do Frontend

Abra um novo terminal e instale as dependências do frontend utilizando npm:

```bash
./vendor/bin/sail npm install
```

#### Passo 6: Rodar o Servidor de Desenvolvimento do Frontend

Ainda no terminal do passo 5, execute o servidor de desenvolvimento do Vue.js:

```bash
./vendor/bin/sail npm run dev
```

### Uso

#### Acessar a Aplicação

Acesse a aplicação no seu navegador através da URL:

```
http://localhost
```

#### Funcionalidades da Aplicação

- **Adicionar Pessoa**: Preencha o formulário de adição de pessoa e clique em "Cadastrar".
- **Pesquisar Pessoa**: Utilize o formulário de pesquisa para buscar pessoas por nome, CPF, ID ou localização.
- **Visualizar Detalhes**: Clique em uma pessoa listada para visualizar seus detalhes.
- **Editar Pessoa**: No modal de detalhes da pessoa, clique no botão de editar para atualizar suas informações.
- **Gerenciar Endereços**: No modal de detalhes da pessoa, clique nos botões para adicionar, editar ou visualizar o histórico de endereços.

### Estrutura do Projeto

- **Backend**: Laravel
- **Frontend**: Vue.js, Vuetify
- **Banco de Dados**: MySQL (configurado via Docker)
- **Gerenciamento de Dependências**: Composer (backend), npm (frontend)
- **Ambiente de Desenvolvimento**: Docker, Laravel Sail

### Comandos Úteis

- **Subir Containers**: `./vendor/bin/sail up -d`
- **Parar Containers**: `./vendor/bin/sail down`
- **Acessar Container do PHP**: `./vendor/bin/sail shell`
- **Executar Migrations**: `./vendor/bin/sail artisan migrate`
- **Instalar Dependências do Backend**: `./vendor/bin/sail composer install`
- **Instalar Dependências do Frontend**: `./vendor/bin/sail npm install`
- **Rodar Servidor de Desenvolvimento do Frontend**: `./vendor/bin/sail npm run dev`

### Contribuição

Faça um fork do projeto
Crie uma branch para sua feature (`git checkout -b feature/fooBar`)
Commit suas alterações (`git commit -am 'Add some fooBar'`)
Dê um push na branch (`git push origin feature/fooBar`)
Crie um novo Pull Request
