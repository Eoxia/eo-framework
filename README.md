# EO-Framework 1.0.0

EO Framework est un framework apportant des fonctionnalités supplémentaires à celui de WordPress.
Gain de temps pour la création et la maintenance de vos plugins WordPress.
Organisation de la structure de vos plugins à la norme de WordPress.

## Pourquoi ?

Nous avons fait en sorte que toutes les tâches répétitifs d'une création d'un plugin et surtout de sa maintenance soit réduite au maximum.

Avec **EO Framework**, nous vous offrons la possibilité d'utiliser très rapidement ce dont tout développeur WordPress à besoin.

## Fonctionnalités

* Bootage de plugin.
* Singleton
* Inclusion de "module" et "external ou bien de plugin dépendant.
* Gestion des vues
* Gestion JSON/CSV
* Gestion de la définition de schéma pour vos données
* Lib JS et CSS
* Log

## Structure des plugins d'Eoxia

* -/core: Dossier contenant les fichiers obligatoires pour le fonctionnement du plugin
* --/external: Dossier contenant les outils externes de votre plugin. Le framework d'Eoxia se trouvera ici.
* --/assets: Vos fichiers assets telles que le css, js, mo et autres ressources.
* --/class
* --/action
* --/view
* -/modules
* --/mon-module-1
* ---/assets
* ----/js
* ---/action
* ---/class
* ---/view
* -mon-plugin.php: Fichier boot lut par WordPress pour initialiser le plugin.
* -mon-plugin.config.json: Fichier boot lut par EO-Framework pour inclure vos fichier automatiquements. (Comparé ça à autoload)

Pour avoir un meilleur visuel rendez vous sur le plugin [Task Manager](https://github.com/Eoxia/task-manager)

## La base d'un plugin WordPress avec EO Framework

Récupérer le dépôt contenant le "starter" d'un plugin WordPress avec EO Framework.

```bash
git clone https://github.com/Eoxia/eo-framework-starter plugin-name && cd plugin-name && rm -rf .git && git init && git submodule add https://github.com/Eoxia/eo-framework core/external/eo-framework
```

Installer [NodeJS](https://nodejs.org/en/)

Ouvrir une console sur votre dossier "plugin-name" puis faites la commande suivante:

```bash
npm install -g gulp && npm install && npm start
```

Cette dernière commande permet de gérer les fichiers CSS ou SCSS et JS. Gulp s'occupe de minifier automatiquement tous vos fichiers css et js en un seul et l'inclus dans le fichier core "action". Nous y reviendrons plus tard.

## La structure de eo-framework-starter

Image | Description
----- | -----------
![Image de la structure du starter](https://github.com/Eoxia/eo-framework-starter/blob/master/core/asset/image/structure_plugin.PNG) | 

Nous utilisons la notion de **module** pour séparer les différentes fonctionnalités de nos plugins. 
Nous avons également comme principe de séparer nos fonctions de nos fichiers selon leurs thèmes:
* Les actions se trouverons dans le dossier 'action'
* Les classes se trouverons dans le dossier 'class'
* Les vues se trouverons dans le dossier 'view'
* Ainsi de suite... |

# Références

## Array_Util
## Config_Util
## CSV_Util
## Date_Util
## Extenral_Util
## File_Util
## Include_Util
## Init_Util
## JSON_Util
## LOG_Util
## Model_Util
## Module_Util
## Post_Util
## Singleton_Util
## View_Util
## ZIP_Util

# WPEO Model
# WPEO Upload

# TODO
* Meilleurs gestion des fichiers JSON
* Pourquoi ?
* Exemple
* Traduction
* Ordre logique du fichier README.md
