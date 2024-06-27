# Chat App

Este é um projeto de chat, desenvolvido em Laravel 11. Aqui você encontrará as configurações básicas e instruções para executar o projeto.

## Configurações básicas

Antes de comerçarmos, certifique-se que você tenha os requisitos mínimos para executar o Laravel 11. Você pode obter mais detalhes na página da documentação disponível em [https://laravel.com/docs].

### Clonando o projeto

Após definir onde o projeto será instalado, abra o terminal e execute o comando do git para clonar os arquivos e em seguida acesse o diretório raiz:

```bash
git clone https://github.com/luizgr/coletek-chat-app
cd coletek-chat-app
```

### Arquivo de configuração

Copie o arquivo `.env.example` e renomeie para `.env`. Em seguida, configure as variáveis de ambiente no arquivo `.env` de acordo com o seu ambiente.

### Banco de dados

O projeto foi desenvolvido em SQLite, mas você também pode optar por outro banco relacional que seja compatível com o Laravel. Defina o caminho do arquivo `.sqlite`:

```bash
DB_CONNECTION=sqlite
DB_DATABASE=**CAMINHO_COMPLETO_DO_ARQUIVO_SQLITE***
DB_FOREIGN_KEYS=true
```

### Dependências

Após configurado o arquivo `.env` atualize as dependências do PHP:

```bash
composer update
```

*Caso não possua o `Composer`, você pode baixá-lo em [getcomposer.org](https://getcomposer.org/).*

### Backend

Gere uma nova chave de criptografia:

```bash
php artisan key:generate
```

Execute o comando para migrar as tabelas:

```bash
php artisan migrate
```
### Frontend

Atualize as dependências do Node:

```bash
npm i
```

Então com as dependências atualizadas rode o *build* do *frontend*:

```bash
npm run build
```

### Pronto para executar

Antes de executar os processos de segundo plano, limpe os caches da aplicação:

```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

Por fim execute os seguintes comandos em segundo plano:

```bash
php artisan serve --port=8000
```
```bash
php artisan reverb:start --port=9000
```
```bash
php artisan queue:work
```