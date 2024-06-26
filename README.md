# Projeto Laravel 11 - Coletek Chat App

Este é o README do projeto Chat App, desenvolvido em Laravel 11. Aqui você encontrará as configurações básicas e instruções para executar o projeto.

## Configurações básicas

1. Certifique-se de ter o PHP instalado em sua máquina. Você pode verificar a versão do PHP 8.2 digitando o seguinte comando no terminal:

```bash
php -v
```

2. Instale o Composer, que é o gerenciador de dependências do PHP. Você pode baixar o Composer em [getcomposer.org](https://getcomposer.org/).

3. Clone este repositório em sua máquina:

```bash
git clone https://github.com/luizgr/coletek-chat-app
```

4. Navegue até o diretório do projeto:

```bash
cd coletek-chat-app
```

5. Instale as dependências do projeto utilizando:

```bash
composer install
```

6. Copie o arquivo `.env.example` e renomeie para `.env`. Em seguida, configure as variáveis de ambiente no arquivo `.env` de acordo com o seu ambiente.

7. Gere a chave de criptografia do Laravel:

```bash
php artisan key:generate
```

8. Execute as migrações do banco de dados:

```bash
php artisan migrate
```

9. Instale as dependências do node e execute:

```bash
npm install 
```

```bash
npm run dev
```

10. Instale a biblioteca reverb do Laravel e inicie:

```bash
php artisan install:broadcasting
```

```bash
php artisan reverb:start
```

11. Inicie a file de processos:

```bash
php artisan queue:work
```

