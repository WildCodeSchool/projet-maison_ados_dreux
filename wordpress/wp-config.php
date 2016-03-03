<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wordpress');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'KL).s?PhV+%OIt|fyHd0*|x<B| :u#ttGxnGe-BB&[)B}: tr^_3|T7,0V$q{`S_');
define('SECURE_AUTH_KEY',  'i9:>X2&|HueW{)kz?)<VT7+KC>xmJ$_/16YR&>,V~s*NyI<ydort+TMK:_yZ.p;{');
define('LOGGED_IN_KEY',    'Ze357*@lji/})@_wr:%mc57T8)$h=9i:06^gW_tX{U=GNH+RQk>MXO3iJXUXBOpK');
define('NONCE_KEY',        'cBKr#z )Sd>j/IJ!Cd7vhv[F=&n*phk6$pakwGBWa nwjoh;X}<LG#~H>N>El,B@');
define('AUTH_SALT',        '>jC<GSWp)onn?fhuu_YZ<;9Z#+bwTOFY-Pl,ax!it[@,~2.0O*#xjvP!iyzht[2[');
define('SECURE_AUTH_SALT', 'J:(LO=_u%BD-jS!?n__Ry}A~?hU/[yzxY5JHv:rReS5I3%tzj+rl%q6AbZRvb8^~');
define('LOGGED_IN_SALT',   '|b!h7NOPG+RHUnI}*lU<U~N3_gCSIs_G[PJCGVu*8jzH|b g3GFUOwV-|N(q~}0-');
define('NONCE_SALT',       '8P)~-/+r&%0)Zp&MuUs,1Wr~kq&{41FOg)9/xdqT^-gkBYr;H1|-v8[7thZtGpg]');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');