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
define('DB_PASSWORD', '');

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
define('AUTH_KEY', ' (E+A(&.Y@0A6S!dm6]p/+:9K|zFv1e^AkO|^[O)-98J35j?+AYscuGw(:9~tq$Z');
define('SECURE_AUTH_KEY', '@CUG!>#X-||ex caP-.xxN~$)]n`okaV8+g.X*;md;@^0*l:VKH|<)9Km:p<)+|@');
define('LOGGED_IN_KEY', '0q,N`~B4[PUxC8<:ts)CQ^prmgQ-bX[kef6R5D|mE8q|Vh!`g3Lj8Uc/74;+IA|H');
define('NONCE_KEY', 'ikbv|.&_8DSRoplRczw XW8#|f ioOt>*KwuFfrq%AEBp+!]JQIbAlX]Bf=wD=&l');
define('AUTH_SALT', 'M4mdwbidh=D5J#c_q|L?mutx=(d?5VXMR%Wl>/v,e)<5BdtT|@Ey5wB:{>gs&iL)');
define('SECURE_AUTH_SALT', 'gL(>QamFtdDC}2$+id,KD<A paU1ZbOIFcg:3ND9R#+O{{]pQ p[~]EtzKk`VXtY');
define('LOGGED_IN_SALT', 'rAGAz:-c[T,^!{%imEWrofUN]fX?~G7{sVP/lfC[^T%ETAP]rf}CwOU{nE<rocb|');
define('NONCE_SALT', '%=i@(l}:0IIvdr;?VmcisqY-Xp,9zyJQ{XmC3qROs|rgk?gm%t3d8zT]D<{2kVq:');

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

