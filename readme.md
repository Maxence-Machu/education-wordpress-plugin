# Création de plugins Wordpress

## Les shortcodes 

> Grâce aux shortcodes, vous pourrez avoir un contenu plus dynamique et aussi plus facilement gérable. Je m’explique : vous avez un code HTML/CSS affichant un élément que vous placez souvent dans vos articles, mais vous devez le copier dans chaque article à l’aide de l’éditeur, ce qui est loin d’être pratique. Alors qu’avec un shortcode, la personne en charge de la rédaction des articles n’aura plus qu’à placer le shortcode correspondant à l’élément voulu, sans devoir y écrire la moindre ligne de code.
>
> En fait, le shortcode WordPress fonctionne un peu comme un raccourci. Il permet de stocker du code pas forcément sexy dans un pseudo langage compréhensible. La puissance du shortcode vient aussi du fait qu’on peut les concevoir nous-même et qu’il est aussi possible de leur passer des paramètres, afin d’avoir une plus grande flexibilité.

[Karac.ch](https://karac.ch/blog/creer-ses-propres-shortcodes-wordpress)

### Shortcode basique

Ce premier shortcode sera un shortcode basique et ne contiendra aucun paramètre. 

#### Ou placer son shortcode ?

Un shortcode peut être placé directement dans le fichier `function.php` mais en cas d'erreur, c'est tout votre site wordpress qui risque d'être hors-service. 

Nous allons utiliser une méthode bien plus agile et réutilisable, celle du `plugin`

#### Création du plugin

Créez un nouveau dossier `basic-shortcode` le dossier `wp-content/plugins` ainsi qu'un fichier `index.php`


```php
<?php
/*
  Plugin Name: Basic Shortcodes
  Description: Plugin fournissant des shortcodes
  Author: maxence_mch
  Version: 1.0.0
 */
```
Nous n'avons plus qu'à activer le plugin dans le panneau d'administration `Extensions` de **Wordpress**

#### Afficher du texte dans un shortcode 

Pour notre premier shortcode nous allons insérer du texte


```php
<?php
/*
  Plugin Name: Basic Shortcodes
  Description: Plugin fournissant des shortcodes
  Author: maxence_mch
  Version: 1.0.0
 */

function shortcode_spacemeteo(){
    return "<h2>Il fait aujourd'hui -40°C sur mars 👨‍🚀</h2>";
}
add_shortcode('spacemeteo', 'shortcode_spacemeteo');
```

Pour le tester, rien de plus simple, insérez `[spacemeteo]` dans une page ou un article.

#### Ajouter des paramètres

Les attributs sont envoyés à notre fonction, nous pouvons les récupérer et les traiter par la suite. 

```php
function shortcode_spacemeteo($atts){
  extract(shortcode_atts(
    array (
      'planet' => 'Mars'
    )
  ,$atts));

  $temperature;
  switch($planet){
    case "Mars":
      $temperature = '-40°C';
      break;
    case "Earth":
      $temperature = '15°C';
      break;
  }

  return "<h1> Il faut aujourd'hui " . $temperature . " sur " . $planet . "👨‍🚀</h1>" ; 
}
```

## Les widgets

Ici nous utiliserons l'API `http://api.open-notify.org/iss-now.json` comme exemple.

Notre widget devra pouvoir afficher la position actuelle de l'ISS (Station Spaciale Internationale)

### Classe et structure

Dans un nouveau dossier `nasa-plugin` nous créerons le fichier `index.php` ainsi que `iss-location-widget.php`.

Dans notre fichier [index.php](nasa-plugin/index.php) nous initialiserons notre plugin grâce au contructeur de notre classe ainsi que l'action Wordpress `widgets_init`.

Cette structure va nous nous permettre, dans le futur, d'instancier plusieurs modules à notre plugin "NASA".

Quand à elle, la création du fichier [index.php](nasa-plugin/index.php) se fait en 4 étapes: 

1. Utiliser le constructeur `__construct()` pour définir le comportement de base du widget
2. Utiliser la fonction `widget()` pour définir le rendu visuel
3. La fonction `form()` pour créer les paramètres dans l'admin

____
Pour plus d'informations: [https://premium.wpmudev.org/blog/create-custom-wordpress-widget/]()

___
___
## L'interface d'administration

### L'ajout au menu 

Pour ajouter une interface d'administration à notre plugin nous utiliserons l'action `admin_menu`. 

Le code suivant est ajouté au contructeur de la classe `NASA_Plugin` 

```php
      add_action('admin_menu', array($this, 'add_admin_menu'));
```

Nous créons ensuite la fonction privée `add_admin_menu`.

La création du menu se fait via la fonciton `add_menu_page`.

Les paramètres de cette fonction sont: 

* Le titre de la page sur laquelle nous serons redirigés
* Le nom dans le menu 
* L'intitulé des droits que doit posséder l'utilisateur pour pouvoir accéder au menu. Si les droits sont insuffisants, le menu sera masqué
* La clé d'identifiant du menu qui doit être (unique)
* La fonction à appeler pour le rendu de la page
* L'icône à utiliser pour le lien (facultatif)
* Position dans le menu (facultatif)


```php
<?php
public function add_admin_menu()
{
    add_menu_page('NASA plugin configuration', 'NASA plugin', 'manage_options', 'nasa', array($this, 'menu_html'));
}
```

Il ne nous reste plus qu'à écrire la fonction `menu_html()` qui va afficher le contenu de notre page d'administration

### Ajout d'un sous-menu 

Pour ajouter un sous-menu, rien de plus simple ! 

Ajouter la fonction suivante à `add_admin_menu(){}` [(voir index.php)](nasa-plugin/index.php)

```php 
      add_submenu_page(
        'nasa', 
        'Sous-Menu', 
        'Sous-Menu', 
        'manage_options', 
        'nasa-params', 
        array($this, 'parametres_html')
```

Puis créez la fonction `parametres_html()` où nous ajouterons simplement, le titre de notre page.

### Paramètres 

* [Wordpress Settings API](https://codex.wordpress.org/Settings_API)
* [3 Approaches To Adding Configurable Fields To Your WordPress Plugin](https://www.smashingmagazine.com/2016/04/three-approaches-to-adding-configurable-fields-to-your-plugin)
* [Wordpress Database](https://codex.wordpress.org/Creating_Tables_with_Plugins)

## Exercice 

En utilisant l'API **APOD (Astronomy Picture of the Day)** de la NASA (https://api.nasa.gov/) vous développerez un plugin permettant d'afficher sous forme de shortcode + widget l'image du jour ainsi que son descriptif et son image.
