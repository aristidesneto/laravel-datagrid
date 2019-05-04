# Laravel DataGrid

Passos para rodar a aplicação:

1. Faça o clone desse repositório: `git clone https://github.com/aristidesneto/laravel-datagrid.git`

2. Instalar dependências: `composer install`

3. Cria arquivo de configuração: `cp .env.example .env`

4. Gera chave de segurança: `php artisan key:generate`

5. Editar arquivo `.env` para:

   ```
   DB_CONNECTION=mysql
   DB_HOST=laravel-datagrid-mysql
   DB_PORT=3306
   DB_DATABASE=datagrid
   DB_USERNAME=datagrid
   DB_PASSWORD=datagrid
   ```

6. Subir container docker: `docker-compose up -d`

7. Permissão na pasta storage: `chmod -R 777 storage`

8. Criar database e rodar as migrations: `docker-compose exec php-fpm php artisan migrate --seed`

9. Abrir a aplicação em: https://localhost:8080

10. Login: admin@admin.com / Senha: secret