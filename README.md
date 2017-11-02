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

## La base d'un plugin WordPress avec EO Framework

Récupérer le dépôt contenant le "starter" d'un plugin WordPress avec EO Framework.

```bash
git clone https://github.com/Eoxia/eo-framework-starter plugin-name && cd plugin-name && rm -rf .git && git init && git submodule add https://github.com/Eoxia/eo-framework core/external/eo-framework
```

Installer [NodeJS](https://nodejs.org/en/) puis ouvrir une console sur votre dossier "plugin-name" puis faites la commande suivante:

```bash
npm install -g gulp && npm install && npm start
```

Cette dernière commande permet de gérer les fichiers **CSS** (ou SCSS) et **JS**.

## La structure de eo-framework-starter

Image | Description
----- | -----------
![Image de la structure du starter](https://github.com/Eoxia/eo-framework-starter/blob/master/core/asset/image/structure_plugin.PNG) | La structure sur l'image *ci-contre* est celle que nous venons de télécharger.<br /><br />Nous avons les deux dossiers principaux "core" et "module". Nous considérons le dossier "core" comme un *module*.<br /><br />Nous utilisons la notion de **module** pour séparer les différentes fonctionnalités de nos plugins.<br />Nous avons également comme principe de séparer nos fonctions de nos fichiers selon leurs thèmes<br /><br />Les **actions** se trouverons dans le dossier 'action'<br />Les **classes** sont dans le dossier 'class'<br />Les **vues** sont dans le dossier 'view'<br />Les **assets** sont dans le dossier 'assets' (Ce dossier contient les ressources du module: JS, CSS, Image et autre types de ressources...)<br />Les **filtres** sont dans le dossier 'filtres'<br />Les **shortcodes** sont dans le dossier 'shortcodes'<br /><br />Tout module doit **obligatoirement** contenir un fichier *nom_du_module*.config.json. Sinon celui-ci ne sera pas initialisé par EO-Framework.

## Les fichiers starter.php et starter.config.json

```php
<?php
/*
Plugin Name:  EO Framework Starter
Plugin URI:   https://developer.wordpress.org/plugins/the-basics/
Description:  Un plugin WordPress utilisant EO-Framework.
Version:      0.1.0
Author:       Eoxia
Author URI:   https://eoxia.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  eo-starter-domain
Domain Path:  /languages
*/

/** Des defines utiles pour inclure votre CSS, JS et fichier MO **/
DEFINE( 'PLUGIN_NOM_PLUGIN_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_NOM_PLUGIN_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_NOM_PLUGIN_DIR', basename( __DIR__ ) );

/** Ligne supplémentaire pour utiliser eo-framework **/
require_once( 'core/external/eo-framework/eo-framework.php' );

/** Boot votre plugin; Nous reviendrons sur cette ligne dans les prochains chapitres. **/
\eoxia\Init_Util::g()->exec( PLUGIN_NOM_PLUGIN_PATH, basename( __FILE__, '.php' ) );
```

Ce fichier utilise les headers de déclaration d'un plugin WordPress et définis des variables utiles. Ensuite il initialise eo-framework à la ligne 21 pour ensuite lancer le **boot** du plugin à la ligne 24.

```json
{
	"name": "Starter",
	"slug": "starter",
	"modules": [
		"core/core.config.json",
		"module/hello-world/hello_world.config.json"
	],
	"version": "0.1.0"
}
```

Le fichier JSON est obligatoire pour initialisé le plugin avec EO-Framework. Le slug doit obligatoriement correspondre au nom du fichier **boot** de *WordPress*.

Ensuite le tableau "modules" permet de **communiquer** à EO-Framework les modules à initialiser lors du boot du plugin.

## Le dossier core

Ce dossier est un traité comme un **module** par EO-Framework, il vas d'abord lire le fichier core.config.json.

```json
{
	"name": "Test Core",
	"slug": "core",
	"version": "0.1.0",
	"description": "Les fichiers core du plugin",
	"path": "core/",
	"dependencies": {
		"action": {}
	}
}
```

Ce fichier est différent de starter.config.json car c'est un config.json d'un module.
Le **slug** doit être le nom du fichier lui même.
Le **path** est obligatoire et c'est un chemin à partir du **dossier principale** du plugin.
La clé "dependencies" permet de définir les fichiers à inclure dans le module. Dans notre cas tous les fichiers dans le dossier "action" du module "Test Core" sera inclus.

### Le fichier action principal: core.action.php

Ce fichier inclus les styles et scripts principales de l'application; il est important de comprendre comment GULP gère les assets:

EO-Framework marche avec **GULP** pour minifier automatiquement vos styles et vos scripts.

GULP vas assembler et minifier tous les fichiers JS portant comme extension \*.backend.js pour ensuite sortir le fichier *backend.min.js* qui sera enregistré dans le dossier asset/js du module **core**.

Cette procédure est similaire pour le *CSS*. GULP vas récupérer tous les \*.backend.jss pour sortir le fichier *backend.min.css qui sera à son tour enregistré dans le dossier "asset/css" du module **core**.

Le fichier "core/action/init.js" permet de déclarer l'objet (qui est une sorte de namespace pour éviter les conflits entre vos différents plugins) qui sera utilisé tout le long de votre dévelopement JS. Nous y reviendrons.

En conclusion: Le module **core** permet de gérer les assets, actions, classes, shortcodes, filtres et vues qui selon vous n'on pas leur place dans un module spécifique.

## Le module "hello-world"

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
