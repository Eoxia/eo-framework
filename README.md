# EO-Framework 1.0.0

EO Framework est un framework apportant des fonctionnalités supplémentaires à celui de WordPress.
Gain de temps pour la création et la maintenance de vos plugins WordPress.
Organisation de la structure de vos plugins à la norme de WordPress.

## Fonctionnalités

* Bootage de plugin.
* Singleton
* Inclusion de "module" et "external ou bien de plugin dépendant.
* Gestion des vues
* Gestion JSON/CSV

## Pourquoi ?



# Pour commencer

Nous utilisons la notion de **module** pour séparer les différentes fonctionnalités de nos plugins.
Nous avons également comme principe de séparer nos fonctions de nos fichiers selon leurs thèmes:
* Les actions se trouverons dans le dossier 'action'
* Les classes se trouverons dans le dossier 'class'
* Les vues se trouverons dans le dossier 'view'
* Ainsi de suite...

## Modules

Les modules représentent une fonctionnalité dans un plugin.

Les modules sont des bouts de code qui permette d'effecuter une fonctionnalité précise dans vos plugins.

## Singleton

Singleton_Util

## Modules

Les modules représentent une fonctionnalité dans un plugin.

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

# Docs WPEO Util 1.x.x

## Créer un plugin WordPress avec WPEO_Util

## Créer un module pour un plugin WordPress

## Application exemple

# TODO

* Explication
* Exemple
* Documentation

