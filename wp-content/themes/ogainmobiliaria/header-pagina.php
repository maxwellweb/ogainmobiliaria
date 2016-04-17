<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ogainmobiliaria
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="cats main-nav">
  <h1><a href="<?php echo home_url( '/' ); ?>" title="Volver al Inicio">Oga Inmobiliaria</a></h1> <!-- LOGO -->
  <nav class="navigation" role="navigation">
    <div class="toggle">Menu</div>
    <ul>
   		<?php wp_nav_menu(array('theme_location' => 'primary', 'walker' => new Mi_menu_walker));  ?>
    </ul>
  </nav>
</header>
