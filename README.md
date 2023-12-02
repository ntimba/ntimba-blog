# Projet 5 - Créez votre premier blog en PHP

## Prérequis
Pour débuter ce projet, assurez-vous que votre environnement de développement dispose des éléments suivants :
- **PHP 8.2** ou supérieur
- **MySQL 8.0** ou supérieur
- Un serveur web tel que **Apache** ou **Nginx** (si vous ne comptez pas utiliser Docker)
- **Composer** : suivez les instructions d'installation sur le [site officiel de Composer](https://getcomposer.org/download/).
- **Docker** : si vous prévoyez d'utiliser Docker, les instructions d'installation sont disponibles sur le [site officiel de Docker](https://docs.docker.com/engine/install/).
- **phpdotenv** pour la gestion des variables d'environnement

## Description
**Mon Blog Personnel** est une plateforme de blogging simple et intuitive, développée avec PHP pur et HTML, conçue pour une expérience d'écriture et de lecture agréable sans dépendances lourdes.

## Technologies Utilisées
Ce projet intègre les technologies suivantes :
- **PHP 8.2** (sans framework)
- **HTML5**
- **CSS3**
- **SASS** pour le style
- **Composer** pour la gestion des dépendances
- **Bootstrap 5** pour le design responsive
- **phpdotenv** pour la gestion des variables d'environnement
- **PHPMailer** Pour l'envoi des mails

## Dépendances
Les dépendances du projet incluent :
- **Composer** pour PHP
- **Bootstrap** et **SASS** pour le front-end

## Installation
Pour installer le projet, clonez le dépôt Git sur votre serveur local :
`git clone https://github.com/ntimba/ntimba-blog.git`

## Configuration du fichier .env
Créez un fichier `.env` à la racine du projet et y insérer les informations suivantes, adaptées à votre configuration :
```
DB_HOST="mysql"
DB_NAME="blog"
DB_USERNAME="root"
DB_USER_PASSWORD="rootpassword"
DB_ROOT_PASSWORD="rootpassword"
PMA_DB_ROOT_PASSWORD="rootpassword"
DB_PORT="rootpassword"
```

## Configuration de la base de données
Importez le fichier blog.sql dans votre système de gestion de base de données pour initialiser la structure nécessaire.

## Démarrage de l'application
Suivez ces instructions pour lancer votre blog, que vous utilisiez Docker ou un serveur LAMP traditionnel.

### Avec Docker
1. Assurez-vous que Docker est installé.
2. Démarrer l'application avec : `docker-compose up -d` 

### Sans Docker
Prérequis : PHP 8.2, MySQL 8.0, Apache

1. Installez PHP, MySQL et Apache.
2. Créez une base de données et un utilisateur.
3. Copiez .env.example en .env et configurez vos variables d'environnement.
4. Installez les dépendances avec : `composer install`
5. Démarrer le serveur web en suivant les instructions spécifiques à votre configuration

## Licence
Ce projet est sous licence MIT. Pour plus de détails, consultez le fichier [LICENSE.md](./LICENSE.md).

## Site Web
Le site est consultable à l'adresse suivante : [ntimba.me](https://ntimba.me)








