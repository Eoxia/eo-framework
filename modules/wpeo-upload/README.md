# wpeo-upload

Shortcode et framework complet CSS/JS pour gérer les medias et la gallery d'un élement (POST, COMMENT, USER ou TERM) dans vos créations de plugin WordPress.

# Docs WPEO Upload 1.0.x

Gestion de l'upload de ressource dans WordPress en utilisant wp.media.

WPEO Upload est dépendant de:
* [WPEO_Model](https://github.com/Eoxia/wpeo_model) >= 1.4.0
* [WPEO_Util](https://github.com/Eoxia/wpeo_util) >= 1.0.0

## Fonctionnalités

* Shortcode
* Upload de fichier dans tous les types de WordPress (POST, COMMENT, USER, TERM)
* Upload de plusieurs fichiers dans tous les types de WordPress (POST, COMMENT, USER, TERM)
* Galerie de navigation avec différentes actions:
  * Changer le thumbnail
  * Uploader un autre fichier
  * Dissocier un fichier

# Shortcode

Le shortcode __[wpeo_upload]__ permet d'utiliser WPEO Upload directement dans vos templates.

Les différents paramètres:
* __id__ (integer) : Le post ID. *defaut: 0*
* __field_name__ (string) :    Le champ ou vas être enregistrer les ID des ressources. *defaut: thumbnail_id*
* __model_name__ (string) :    Obligatoire pour WPEO_Model. *defaut: \eoxia\Post_Class*
* __custom_class__ (string) :  Utiles si vous devez utiliser plusieurs fois le bouton dans un même template pour le même POST. *defaut: empty*
* __size__ (string)         :  Comme les tailles de WordPress: thumbnail, medium, full. *defaut: thumbnail*
* __single__ (string)       :  Si vous voulez utiliser la galerie ou pas. *defaut: true*
* __mime_type__ (string)    :  Permet de définir le mime_type des fichiers à upload et de filtrer la vue de wp.media. *defaut: empty*

## Exemple d'utilisation

Association d'une seule image dans le champ 'thumbnail_id' pour le *POST* 1.

__[wpeo_upload id=1]__

Association de plusieurs image dans le champ associated_document['images'] pour le *POST* 1

__[wpeo_upload id=1 single="false" field_name="images"]__

Association d'une seule image dans le champ 'thumbnail_id' pour le *POST* 1.

__[wpeo_upload]__

Association d'une seule image dans le champ 'thumbnail_id' pour le *POST* 1 en utilisant l'objet *Model_Class* dans le namespace *namespace*.

__[wpeo_upload id=1 model_name="/namespace/Model_Class"]__

# Le paramètre boolean "single"

Single permet de définir si l'élément peut contenir plusieurs ressources ou au contraire, uniquement une seule.

## SI true

Le POST ne peut contenir qu'une __seule__ ressource qui sera enregistrée dans __thumbnail_id__.

## SI false

Le POST peut contenir __plusieurs__ dans une meta qui sera défini par __field__name__. Attention, le champ par défaut __thumbnail_id__ de WordPress ne permet pas d'enregister un tableau d'ID.
Pour utiliser le paramètre __single__ à __false__, il faut obligatoirement définir le paramètre __field_name__.

# Peut-on avoir plusieurs ressources dans un seul élement ?
Oui. Il est important de comprendre que si __single__ est à __false__ vous pouvez enregistrer plusieurs ressources sur l'élément. Seulement vous ne pouvez pas définir __plusieurs__ shortcodes pour un élement.

# Utiliser WPEO_Upload sans shortcode

Toutes les fonctions qui suivent se trouve dans l'objet __wpeo-upload.class.php__ dans le dossier *class*

Le paramètre **$model_name** est expliqué dans la documentation de [WPEO_Model](https://github.com/Eoxia/wpeo_model/).

## Associer une ressource au thumbnail pour un element

WPEO_Upload_Class::g()->set_thumbnail( **$id, $file_id, $model_name** );

* integer $id L'ID de l'élement ou la ressource sera associé. (Ne peut pas être vide)
* integer $file_id L'ID de la ressource.
* string $model_name Le modèle à utiliser.

## Associer une ressource au tableau associated_document['image']

WPEO_Upload_Class::g()->associate_file( **$id, $file_id, $model_name, $field_name** );

* integer $id L'ID de l'élement ou la ressource sera associé. (Ne peut être vide)
* integer $file_id L'ID de la ressource. (Ne peut être vide)
* string **$model_name** Le modèle à utiliser. [WPEO_Model](https://github.com/Eoxia/wpeo_model/).
* string **$field_name** Le nom du champ de la meta ou sera enregistré les ressources. Ce champ doit être défini dans la définition de votre modèle. Voir [WPEO_Model](https://github.com/Eoxia/wpeo_model/).

## Dissocier une ressource au tableau associated_document['image']

WPEO_Upload_Class::g()->dissociate_file( **$id, $file_id, $model_name, $field_name** );

* integer $id L'ID de l'élement ou la ressource sera dissocié. (Ne peut être vide)
* integer $file_id L'ID de la ressource. (Ne peut être vide)
* string **$model_name** Le modèle à utiliser. [WPEO_Model](https://github.com/Eoxia/wpeo_model/).
* string **$field_name** Le nom du champ de la meta ou sera enregistré les ressources. Ce champ doit être défini dans la définition de votre modèle. Voir [WPEO_Model](https://github.com/Eoxia/wpeo_model/).

## Récupéres le template de la galerie pour un élement

WPEO_Upload_Class::g()->display_gallery( **$id, $model_name, $field_name, $size = 'thumbnail', $single = false, $mime_type = '', $custom_class = ''** );

* integer $id L'ID de l'élement ou la ressource sera dissocié. (Ne peut être vide)
* string **$model_name** Le modèle à utiliser. [WPEO_Model](https://github.com/Eoxia/wpeo_model/).
* string **$field_name** Le nom du champ de la meta ou sera enregistré les ressources. Ce champ doit être défini dans la définition de votre modèle. Voir [WPEO_Model](https://github.com/Eoxia/wpeo_model/).
* string $size La taille de la ressource affichée. Peut être thumbnail, medium ou full. Par défaut thumbnail.
* boolean $single Voir le point de cette documentation # Le paramètre boolean "single". Par défaut false.
* string $mime_type Permet de définir le mime_type des fichiers à upload et de filtrer la vue de wp.media. *defaut: empty*

# TODO

* Ajouter le CSS
