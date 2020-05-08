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
  - J'ai exécuter les commandes "composer install", ~~"npm install"~~, "copy .en.example .env" et "php artisan key:generate"
  - J'ai modifier le .env.example pour que l'importation soit plus simple

## 24.04.2020
  - création d'un seeder pour l'utilisateur
    - erreur Call to undefined function str_random()
      - $ composer require laravel/helpers
  
## 27.04.2020
  - Création d'une fonction pour sérialiser les information des plongées dans un tableau json
## 28.04.2020
  - création de la page utilisateur
    - j'ai essayer d'utiliser un template bootstrap mais il n'était pas compatible avec laravel
    - j'ai donc refait la page utilisateur et club à la main
## 29.04.2020
  - création de la page contenant la liste des plongées
    - j'ai du refaire la requête car les valeurs retournées n'étaient pas suffisante
    - je n'ai eu le temps de faire que la partie public de la liste
## 30.04.2020
  - création de la page plongées

## 01.05.2020
  - modification de la requête de la plongée pour récupérer des informations suplémentaires
  - modification de la date en php pour un affichage lisible
  - début de l'import du fichier xml
    - lors de l'import du fichier il semble ne pas être déplacer dans le dossier cache avant les traitement
    - je n'avais pas mis de name à mon input.

## 04.05.2020
 - correction de la listes des plongées qui ne s'affichent pas toutes
 - ajout des listes de plongées personnels

## 05.05.2020
 - réunion avec M. Mathieu
 - recherche pour le Poster

## 06.05.2020
 - Le poster que j'ai fait la veille ne me convient pas, je décide de recommencer
   - je fais un croquis pour me donner une idée de comment va se construire le poster
 - La v2 n'est pas assez explicite, l'on ne comprend pas le projet.
 - La v3 est mieux compréhensible mais elle manque de direction.
 - j'ai fait une v4 et une v4.1, que j'ai montré à la classe pour voir quelle version ils préfèrent.
 - après un choix unanime j'ai fait quelque modification et l'ai envoyé à M. Mathieu
  
## 07.05.2020
 - Rédaction de l'analyse de l'existant
 - Ajout de Laragon et VSCode dans l'environnement

## 08.05.2020
 - Je ne sais pas pourquoi mais lorsque j'essaie de faire un concat (sql) dans la table following il ne fonctionne pas
   - j'ai confondu concat() avec group_concat()
 - 