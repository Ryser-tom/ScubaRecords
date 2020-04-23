# JOURNAL DE BORD

## 06.04.2020
### Installation logiciels
- Laragon
- Workbench
- Visual studio code
- Postman
- Axure RP
  
### Début du projet
- Création du Github
- Création du Trello 
- Création du document word pour la documentation
- Création d'un modèle de base de données

![database v1](/Documentation/Bdd/v1.SVG)

## 07.04.2020
- Création des maquettes

## 20.04.2020
- Premier meeting avec M.Mathieu
  - Recherche sur ce que sont des tags de base de donnés
- Retranscription du carnet de bord en markdown

## 21.04.2020
- Modification de la base de données pour fonctionner avec des tags

## 22.04.2020
- Début de l'api (création de la base de données dans laragon)
  - ~~ erreur lors de l'import de la base de données depuis workbench ~~
    - Le problème venait du fait que workbench utilise la version 8 de mysql et laragon la version 6, j'ai alors changé la version dans workbench.
    - Un autre du nomage des clé étrangères, j'ai donc opté pour un nommage du style suivant (fk_tableActuel_CléPrimaire)

## 23.04.2020
- Recherche sur la création d'api avec laravel
  - https://www.toptal.com/laravel/restful-laravel-api-tutorial 
- ~~ Lors de chaque requêtes laravel fait appel à la table demandée mais rajoute un "s" à la fin. ~~ 
  - Laravel utilise un système de prédiction, qui dit que chaque table doit être au pluriel. Il est possible de forcer l'utilisation d'un nom avec la ligne suivante "protected $table = 'club'"
- ~~ Laravel utilise comme index par défaut "id" ~~  
  - Pour choisir un index il faut rajouter la ligne "protected $primaryKey = 'idClub'"
- ~~ lors de requêtes post j'obtien une erreur avec un champ updated_at ~~ 
  - il m'a fallut ajouter la ligne suivante dans mon model pour en annuler l'obligation "public $timestamps = false" 
- après ma tentative de réinstallation pour l'évaluation intermédiaire lors de l'accès au site, j'obtien l'erreur : 
  
  "Warning: require(C:\laragon\www\ScubaRecords-master\public/../vendor/autoload.php): failed to open stream: No such file or directory in C:\laragon\www\ScubaRecords-master\public\index.php on line 24

  Fatal error: require(): Failed opening required 'C:\laragon\www\ScubaRecords-master\public/../vendor/autoload.php' (include_path='.;C:/laragon/etc/php/pear') in C:\laragon\www\ScubaRecords-master\public\index.php on line 24" 
  - J'ai exécuter les commandes "composer install", "npm install", "copy .en.example .env" et "php artisan key:generate"

- 
     