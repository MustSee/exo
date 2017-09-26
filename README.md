TinyUrl
=======

A Symfony 3 project created on September 21, 2017, 10:47 pm.

<h1>Installation</h1>
Après avoir récupéré le code en clonant le dépôt GIT ou en téléchargeant le code source en archive zip,
<h5>1. Définir vos paramètres d'application</h5>
Pour ne pas qu'on se partage tous nos mots de passe, le fichier app/config/parameters.yml est ignoré dans ce dépôt. A la place, vous avez le fichier parameters.yml.dist que vous devez renommer (enlevez le .dist) et modifier.
<h5>2. Télécharger les vendors</h5>
Avec composer : 
$ php composer.phar install
<h5>3. Créer la base de données</h5>

Si la base de données que vous avez renseignée dans l'étape 1 n'existe pas déjà, créez-la :

$ php bin/console doctrine:database:create

Puis créez les tables correspondantes au schéma Doctrine :

$ php bin/console doctrine:schema:update --dump-sql

$ php bin/console doctrine:schema:update --force
<h5>4. Publiez les assets dans le répertoire web</h5>
$ php bin/console assets:install --symlink

