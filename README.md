# SUBLET - Projet Intéropérabiltié

> NOTE: Les deux projets partagent la même arborescence et ont donc les mêmes instructions de déploiement.

## Routes

Pour chaque route, le `.php` est optionnel.

- `/velos.php` (défaut): Infos météo et sur les vélos de Nancy
- `/circulations.php`: Infos sur la circulation du virus et en Loire Atlantique

## Production

Aucun `Dockerfile` n'est prévu pour le déploiement. Il faut passer par un serveur externe (Apache par ex.) et rediriger toutes les requêtes (au moins PHP) vers `/index.php` et installer les dépendances `composer` avec :

```sh
composer install
```

> NOTE: Si vous avez récupéré le projet via une archive `zip`, les dépendances `composer` sont déjà installées

## Développement

### Docker

```sh
composer install  # Pre-installed with zip file
docker-compose up --build d
# Setup live reload
yarn
yarn dev
```

### PHP

```sh
composer install  # Pre-installed with zip file
php -S 0.0.0.0:8080
# Setup live reload
yarn
yarn dev
```
