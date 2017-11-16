<?php
/**
 * Plugin Name: Ejemplo de Principiante
 * Description: Este plugin modifica los titulos de las entradas.
 * Plugin URI: http://wprincipiante.local
 * Author: Bexandy Rodríguez
 * Author URI: http://www.bexandy.com.ve
 * Version: 1.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wprincipiante-ejemplo
 * Domain Path: /languages/
 * Network: false
 */

defined( 'ABSPATH' ) or die('¡Sin trampas!');

if (is_admin()) {
  require_once('admin/meta-box-extension-titulo.php');
} else {
  //require_once('public/cambia-titulos.php');
  require_once('public/enviar-club-ajax.php');
}

