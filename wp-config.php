<?php
/**
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'ogainmo');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'sofiaxiomara');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '-Q*pa+E^MSLc g(,1vB}(mm!%l -{=Q2i*Td-C+-b#%oUoEW5bK3I6e+b+|UuV(_');
define('SECURE_AUTH_KEY', '{_$J;s5XjX7(Q|0Mh|[iK@]!6VkP&T?6W636piB;8%oRmg.H6V)Ev]0c^t7 hT`K');
define('LOGGED_IN_KEY', '`n{U2Mb?|)|,%~xLj?DeEIBpgX`PdJS&ZADIPtMx?p-R1m0wM{#,~laGm6bko6~E');
define('NONCE_KEY', ';B[|Kz#!S.|WSMcv=!sSG!F ]2&a4v<T.rYn;+`PlzR>-A=!(/|FrZNd9o78UCXc');
define('AUTH_SALT', 'PrMm-Q~egpIyhK9zE8Ml4yQ1Y2@:wTJ_>=bCQw4hPp+0GC{~jV)|tm&[?^iy_5P*');
define('SECURE_AUTH_SALT', 'W]a|;udEc^(Mjo=-2J[^=IIZ@#a!F9H9g_KeAp|)PV+!O<=E-i?n#9}C%FC7-TDO');
define('LOGGED_IN_SALT', 'xXSe1ND#nBUG(qA}<IILUh[+N>;tzk 0d`O,5(W3{Kb-~tIZ[)o`^UUB}J8,g,2f');
define('NONCE_SALT', '~t,?b4Xw|Pfve:#{We+R.Xgp(Gnp*q7D(JX}+Z@8Fit@6[wfmH *g{|f3z+PAf-r');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
