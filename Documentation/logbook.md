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
  - après réflexion je ne pense pas avoir besoin des tables: location, country, languages.

## 11.05.2020- reprise de l'insertion des données depuis un fichier uddf

## 13.05.2020
  - Les plongées insérée depuis un fichier ne sont pas récupérée pour l'affichage.
    - j'avais modifier ma base de données, donc je n'utilisait plus la table 'locations'.
  - j'ai une erreur undefined offset: 0 et il semble que xdebug pose problème.
    - Pour corriger j'ai du rajouter une clause au where pour que l'utilisateur puisse voir ces plongée privée
  ```
  ->where(function($q) use ($user) {
    $q->where('dives.public', 1)
    ->orWhere('dives.diver', $user->idUser);
  ```
## 14.05.2020
  - problème pour convertir les "samples" de suunto à uddf
    - je n'arrive pas à enregistrer les waypoint car lorsque j'essaye de créer un node avec le même nom, il me crée un array.
    - pour palier à ce problème j'ai créer une fonction de formatage appelée formatXml().
  - après l'insertion j'ai des valeurs vides dans les dives_tags

## 15.05.2020
  - création de la map avec leaflet
    - la map ne s'affiche pas, et j'ai une erreur avec le json parse
    - il semblerait qu'eloquent retourne un objet javascript (inutile de parse)

## 18.05.2020
  - modification de la map pour utiliser geoJSON ainsi qu'utiliser le markercluster plugin pour améliorer l'affichage ( https://digital-geography.com/working-with-clusters-in-leaflet-increasing-useability/ )

## 19.05.2020
  - Form inconnu utiliser {{ Form::hidden('invisible', 'secret') }} (input invisible)
    - ajout de laravelcollective/html par composer (composer require laravelcollective/html)
  
## 20.05.2020
  - l'affichage du formulaire du club ne s'affiche pas en 2 colonnes comme il le devrait
    - laravel utilise bootstrap 3.3.6 par défaut, et le système de grid as changé, je pensait être en 4.4.1

## 21.05.2020
  - Après la modification d'une plongée il semblerait que je perde des "dive_tags"
    - Impossible de re créer l'erreur.
  - Erreur lors de la récupération de la date pour le formulaire de modification
    - Création de $date avec une concaténation des éléments obtenu après l'explode du dateTime.
  - Problème lors de la redirection, je me retrouve sur une page 404
  - problème lors de la récupération des données, impossible de faire appel à ->toArray()

## 22.05.2020 Vendredi
  - 

## 23.05.2020
  - 

## 24.05.2020
  - 

## 26.05.2020
  - Erreur lors de l'affichage du graphique, unexpected token ':'
    - pour corriger l'erreur j'ai placé le contenu de la plongée dans un string (entre "")
  - Puis j'obtien l'erreur SyntaxError: "" string literal contains an unescaped line break

## 27.05.2020 
  - erreur 403 après avoir transféré le site sur le serveur.
    - https://www.laravel.fr/t/besoin-daide/403-forbidden-1
  - erreur Class 'Illuminate\Routing\RouteAction' not found
    - $composer update
  - ErrorException
file_put_contents(C:\laragon\www\ScubaRecords\storage\framework/sessions/nR6sMadSdDTRUeNi89GwASI9mpvxkwcOh7RsmcnT): failed to open stream: No such file or directory 
    - J'ai utilisé la commande suivante pour recréer le cache (le problème viendrais du fait que j'avais deja deploxé un projet au paravant.) $php artisan config:cache 
  - Création de la base de données avec pour identifiant: x671w_scubarecords:iAHqY7VlppptMsF2&LZZgdqHQZ_RXYB
  - erreur highchart 14 (String value sent to series.data, expected Number)
    - j'ai parse toutes les données du graphique avec parseFloat()
  - Le graphique s'affiche et les données sont justes, mais l'échelle horizontale utilise un format datetime et non time
    - j'ai modifier la valeur du temps que je récupérait de la base de données et l'ai traité différament pour en faire un date time en secondes.
  - Lors de la tentative de création d'un compte depuis le site sur l'hébergeur j'ai une erreur de connexion refusée
    - lors que j'essaye de faire une requête à la base de données avec pdo (depuis le site) cela fonctionne, mais les requêtes eloquent ne fonctionne toujours pas, j'ai contacté le support d'Infomaniak, ainsi que Mme Travnjak pour obtenir de l'aide concernant le sujet.
  - La plongée 60 n'affiche pas le graphique mais ne retourne pas d'erreurs
    - Le problème semble venir de l'import de fichier Aeris, dans les données de profondeur de la plongée.
    - à la ligne 694 j'avais laissé $data[37] à la place de $data[$i]
    - J'avais aussi oublié de break sur la dernière ligne. (une ligne vide)
  
## 02.06.2020
  - Erreur dans la fonction changeMembership "Property [items] does not exist on this collection instance."
    - pour corriger j'ai remplacer "is_null($result)" par "$membership->count()"
  - Sur la page du club le status (follow) ne s'affiche plus
    - j'avais inversé les paramètres de la fonction "getMembership"