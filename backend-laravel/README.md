# Prerequis:

```sh
php >= 7.4
composer
mysql
node 18 or higher
database => princept_i_web_school_db
```

# Installation de projet princept-i-web-school

## cloner d'abord le projet utiliser le commande

```sh
git clone https://gitlab.com/princept-i-web/princept-i-web-school.git

cd princept-i-web-school
```

## Database Configuration

`Create la base de donnée et configure votre .env`

## Pour configurer votre .env

Copier le fichier `.env.example` et nommé `.env` et changer votre acces sur la base de donnée

## Installation des dependences

```sh
composer install
```

## Faire migration pour migrer le table sur la base des données

```sh
php artisan migrate

php artisan key:generate

php artisan passport:install

php artisan passport:install --force

php artisan migrate:fresh --seed
php artisan passport:client --personal
```

## Changer la maximum taille d'upload de fichier sur php.ini(/etc/php/7.4/cli/php.ini)

```sh
Maximum allowed size for uploaded files .

upload_max_filesize = 50M
```

## Link image ou fichier dans le public

```sh
php artisan storage:link
```

## Pour lancer le server

```sh
`php artisan serve`
```

## EndPoint(API) pour l'authentification(utiliser Insomnia ou Postman pour tester les endPoint):

User Register API: Method: `POST`, URL: `http://127.0.0.1:8000/api/register`

```sh
    {
        "email" : "email_user",
        "password" : "password_user",
        "name" : "nom_user"
    }
```

User Login API: Method: `POST`, URL: `http://127.0.0.1:8000/api/login`

```sh
    {
        "email" : "email_user",
        "password" : "password_user"
    }
```

# clientschool

## install dependences

```sh
npm install
```

## Pour configurer votre .env du front

Copier le fichier `.env.example` dans le dossier `clientschool` et nommé `.env`

### change for your BASE_URL by http://127.0.0.1:8000/api/ in `clientschool/src/configs.js`

```sh
export const configs = {
    BASE_URL: "http://127.0.0.1:8000/api/",
    RELEASE: "local",
};
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Compile and Minify for Production

```sh
npm run build
```
