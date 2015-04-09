Mon Appart
==========

La recherche d'appart pour ceux qui n'ont pas envie de chercher.

Et quand on a pas envie de chercher, on fait une application qui connait nos
critères et qui va chercher pour nous (uniquement sur
[Leboncoin.fr](http://leboncoin.fr/) et [avendrealouer.fr](http://avendrealouer.fr/)
pour le moment).

**Important :** ce projet a été réalisé dans le cadre de **ma** recherche
d'appartement et à ce titres certains aspect du code (notamment concernant les
crawlers) sont spécifiques à ma ville de recherche (Lyon). Rendre le code
générique reste cependant largement réalisable.

## Installation

L'application est développée avec Symfony, le détail de l'installation de
l'environnement (PHP, serveur web, base de données, ...) reste à la charge de
l'éventuel utilisateur.

Une fois l'environnement prêt, cloner le projet:

```
git clone git@github.com:K-Phoen/mon-appart.git
```

Installation des dépendances :

```
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize
```

Durant l'installation, Composer devrait vous demander différentes informations
(paramètres de connexion à la base de données, au serveur mail, ...).

La dernière étape consiste à lancer périodiquement la commande qui ira chercher
pour vous l'appartement de vos rêves :

```
crontab -e
@hourly /<<CHEMIN_VERS_LAPPLI>>/app/console --env=prod app:fetch-all >> /<<CHEMIN_VERS_LAPPLI>>/logs/cron.log
```

En remplaçant évidemment `<<CHEMIN_VERS_LAPPLI>>` par le chemin approprié.

## Configuration

Les critères de recherches sont spécifiés dans le fichier `app/config/config.yml` :

```yaml
app:
    criteria:
        area_min:  35
        price_max: 700
        rooms_min: 2
        type:      flat
        locations: [Lyon 69002, Lyon 69003]
```

## Licence

[WTFPL](http://www.wtfpl.net/)
