## TEA Rest Project

Restfull API build on top of Symfony.

### How to set up the project?

1. Clone the repo:

```
  git clone https://github.com/notaphplover/tea-rest.git
```

2. Go to the root folder of the project and install composer dependencies:

```
  cd tea-rest
  composer install
```

__Still dont have Composer?__ [Download](https://getcomposer.org/download/) it now.

3. Set up env variables in the .env file:

- Set the database connection. __This connection must be a MySql DB server connection__.

At the end, our .env file should look like this:

```
APP_ENV=dev
APP_SECRET=MY_APP_SECRET

DATABASE_URL=mysql://<user>:<password>@<host>:<port>/<db_name>?serverVersion=<mysql_version>

```

4. Generate auth certificates in order to sign JWT tokens:

```
  openssl genrsa -out config/jwt/private.pem -aes256 4096
  openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

5. Configure parameters

__tea-rest/config/parameters.yml__
```yaml
parameters:
    jwt_cert_private_path: config/jwt/private.pem
    jwt_cert_public_path: config/jwt/public.pem
    jwt_cert_secret: YOUR_CERTIFICATE_SECRET
```

Thats all!

### How do I start the server?

You can use the project's console to perform that action:

```
bin/console server:run
```

### where are the docs?

Since this is an open API, docs are accessed as API endpoints.

- As an OpenAPI JSON document under GET /api/doc.json
- As an HTML page under GET /api/doc

For example, if you started the server using the project's console, the doc endpoints would be `http://127.0.0.1:8000/api/doc` and `http://127.0.0.1:8000/api/doc.json`.
