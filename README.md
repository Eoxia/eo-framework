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

## La base d'un plugin WordPress

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
* Créer un fichier hello-world.config.json

Dans ce fichier, nous allons écrire plus de paramètre que dans le config.json du plugin:

```json
{
	"name": "Hello World",
	"slug": "hello-world",
	"version": "0.1.0",
	"path": "modules/hello-world"
}
```

Nous allons maintenant créer la page "Hello World" dans le menu WordPress en respectant la norme d'Eoxia (cf #pour commencer)

Créer le fichier hello-world.action.php dans le dossier "action" (toujours dans le module "hello-world")
Ce fichier vas permettre d'appeler l'action *add_menu_page* de WordPress:

```php
<?php
/**
 * Les actions principales du module "hello-world"
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 0.1.0
 * @version 0.1.0
 * @package Test
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions principales de l'application.
 */
class Hello_World {

	/**
	 * Le constructeur
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );
	}


	/**
	 * Ajout du sous menu 'Hello World' dans le menu de WordPress.
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 *
	 * @return void
	 */
	public function callback_admin_menu() {
		add_menu_page( 'Hello World', 'Hello World', 'manage_options', 'hello-world', array( $this, 'callback_add_menu_page' ) );
	}

	/**
	 * Le callback pour afficher la vue.
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 *
	 * @return void
	 */
	public function callback_add_menu_page() {
		echo 'Hello World';
	}
}

new Hello_World();
```

Le module et la page étant déclaré, il faut maintenant dire à notre plugin d'initialiser le module 'hello-world', pour ça nous allons retourner dans le fichier config.json principale du plugin et ajouter les lignes suivantes:

```json
"modules": [
		"modules/hello-world.config.json"
	]
```

Et pour finir, dans le fichier .config.json du module "hello-world" nous allons lui dire d'inclure le dossier "action".

```json
"dependencies": {
		"action": {}
	}
```

Vous avez maintenant votre sous menu "Hello World" dans le menu de WordPress.

## Modules

Les modules sont des bouts de code qui permettent d'effecuter une fonctionnalité précise dans vos plugins. Nous allons y revenir dessus par la suite.

## *.config.json

Les configurations des modules/externals se trouvent dans le fichier .json. Un module ne peut pas boot sans ce fichier.

Les bases de ce fichier JSON sont:

```json
{
  "slug": "mon-module",
  "path": "modules/mon-module"
}
```

**slug** et **path** sont des paramètres obligatoires. Sans ceci WPEO_Util ne bootera pas votre module.

## Externals

Les externals sont comme ce projet, il sont développé comme des modules, seulement ils sont là pour ajouter des fonctionnalités externes à vos plugins.

## Gestion des vues

View_Util

## Gestion JSON/CSV

JSON_Util, CSV_Util

# Utiliser EO-Framework

## Créer un plugin WordPress avec EO-Framework

## Créer un module pour un plugin WordPress

## Application exemple

# TODO

* Pourquoi ?
* Exemple
* Traduction
* Ordre logique du fichier README.md
