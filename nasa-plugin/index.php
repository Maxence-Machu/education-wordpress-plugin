<?php
/*
  Plugin Name: NASA API Plugin
  Description: Utilisation de l'API de la NASA
  Author: maxence_mch
  Version: 1.0.0
 */

include_once plugin_dir_path( __FILE__ ).'/iss-location-widget.php';

class NASA_Plugin
{
    public function __construct()
    {
      /* Widget simple */ 
      add_action('widgets_init', function(){register_widget('ISS_Location_Widget');});

      /* Menu Admin */
      add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    
    }

    public function add_admin_menu(){
      add_menu_page(
        'NASA plugin configuration', 
        'NASA plugin', 
        'manage_options', 
        'nasa', 
        array($this, 'menu_html')
      );

      add_submenu_page(
        'nasa', 
        'Sous-Menu', 
        'Sous-Menu', 
        'manage_options', 
        'nasa-params', 
        array($this, 'parametres_html')
      );
    }

    public function menu_html()
    {
      ?>
        <h1><?php echo get_admin_page_title(); ?></h1> 
        <p>Configuration du plugin NASA</p>
       <?php
    }

    public function parametres_html(){
      ?>
        <h1><?php echo get_admin_page_title(); ?></h1> 

      <?php
    }
}
new NASA_Plugin();