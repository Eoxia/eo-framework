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

# Pour commencer

Nous utilisons la notion de **module** pour séparer les différentes fonctionnalités de nos plugins.

Nous avons également comme principe de séparer nos fonctions de nos fichiers selon leurs thèmes:
* Les actions se trouverons dans le dossier 'action'
* Les classes se trouverons dans le dossier 'class'
* Les vues se trouverons dans le dossier 'view'
* Ainsi de suite...

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

Comme sur la doc de (WordPress.org)[https://developer.wordpress.org/plugins/the-basics/header-requirements/], nous allons retrouver notre fichier principale avec les 'headers' obligatoires pour que le plugin aparaisse dans la page 'Extensions' de votre backend.

```php
<?php
/*
Plugin Name:  WordPress.org Plugin
Plugin URI:   https://developer.wordpress.org/plugins/the-basics/
Description:  Basic WordPress Plugin Header Comment
Version:      20160911
Author:       WordPress.org
Author URI:   https://developer.wordpress.org/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wporg
Domain Path:  /languages
*/

/** Des defines utiles pour inclure votre CSS, JS et fichier MO **/
DEFINE( 'PLUGIN_NOM_PLUGIN_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_NOM_PLUGIN_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_NOM_PLUGIN_DIR', basename( __DIR__ ) );

/** Ligne supplémentaire pour utiliser eo-framework **/
require_once( 'core/external/eo-framework/eo-framework.php' );

/** Boot votre plugin; Nous reviendrons sur cette ligne dans les prochains chapitres. **/
\eoxia\Init_util::g()->exec( PLUGIN_NOM_PLUGIN_PATH, basename( __FILE__, '.php' ) );
```
Voici la base d'un plugin WordPress avec EO-Framework.

Si vous tentez d'activer le plugin, vous aurez une belle 'fatal error'. Tout est normal, il faut inclure eo-framework.

## Inclure eo-framework

Au même niveau que votre fichier boot de WordPress, commencez par créer le dossier 'core' et le sous dossier 'external'.

Faites ensuite la cmd depuis le dossier racine de votre plugin:

git init && git submodule add https://github.com/Eoxia/eo-framework core/external/eo-framework

Vous voila muni de EO-Framework!

### Création du fichier mon-plugin.config.json

EO-Framework s'appuis sur les fichiers \*.config.json pour inclure, initialiser les configurations de votre plugin et vos modules.

Créons le fichier mon-plugin.config.json, le nom du fichier est **important**, il doit être similaire au nom de votre dossier.

```json
{
	"name": "Mon Plugin",
	"slug": "plugin"
}
```

### Nous allons maintenant configurer notre plugin pour utiliser le JS et le CSS (Falcultatif)

Créer un sous dossier "action" dans "core" puis créer le fichier mon-plugin.action.php avec le contenu suivant:


## Une page "Hello World"

Commençons par créer le module "hello-world":
* Créer un sous dossier "hello-world" dans le dossier "modules" qui vous aurez à préalable créer.
* Créer un fichier hello_world.config.json

Dans ce fichier, nous allons écrire plus de paramètre que dans le config.json du plugin:

```json
{
	"name": "Hello World",
	"slug": "hello_world",
	"version": "0.1.0",
	"path": "module/hello-world",
	"dependencies": {
		"action": {}
	}
}
```

Nous allons maintenant créer la page "Hello World" dans le menu WordPress en respectant la norme d'Eoxia (cf #pour commencer)

Créer le fichier hello-world.action.php dans le dossier "action" (toujours dans le module "hello-world")
Ce fichier vas permettre d'appeler l'action *add_menu_page* de WordPress:

```php
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_World_Action {

	/**
	 * Le constructeur
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );
	}


	/**
	 * Ajout du sous menu 'Hello World' dans le menu de WordPress.
	 */
	public function callback_admin_menu() {
		add_menu_page( 'Hello World', 'Hello World', 'manage_options', 'hello-world', array( $this, 'callback_add_menu_page' ) );
	}

	/**
	 * Le callback pour afficher la vue.
	 */
	public function callback_add_menu_page() {
		echo 'Hello World';
	}
}

new Hello_World_Action();
```

Le module et la page étant déclaré, il faut maintenant dire à notre plugin d'initialiser le module 'hello-world', pour ça nous allons retourner dans le fichier config.json principale du plugin et ajouter les lignes suivantes:

```json
"module": [
		"modules/hello_world.config.json"
	]
```

Et pour finir, dans le fichier .config.json du module "hello-world" nous allons lui dire d'inclure le dossier "action".

```json
"dependencies": {
		"action": {}
	}
```

Vous avez maintenant votre sous menu "Hello World" dans le menu de WordPress.

## View_Util

Comme vous pouvez le voir dans votre fichier hello-world.php à la ligne 28 nous faisant un "echo 'Hello World';". Je trouve ça personnelement pas du tout propre. Nous allons plutôt séparer la vue du controller comme tout modèle MVC.

Créons un fichier main.view.php dans le dossier "view":

```html
<div class="wpwrap">
	<h1>Hello World</h1>
</div>
```

Maintenant remplaçons la ligne 28 du fichier hello-wrold.action.php par celle-ci:

```php
\eoxia\View_Util::exec( 'test', 'hello_world', 'main' );
```

Si vous voulez en savoir plus sur View_Util, rendez vous sur le chapitre: Référence -> View_Util

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
