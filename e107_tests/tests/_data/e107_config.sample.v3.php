<?php // e107_config.php format revision: 3 (do not change or remove this line)
/**
 * e107 website system
 *
 * Copyright (C) 2008-2020 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Site configuration file
 */

/**
 * Database Connection Settings
 *
 * The $database array contains the information that e107 needs to access the
 * database associated with this site.
 *
 * @var array $database = [
 *     // Common options
 *     'driver' => 'string', // Case-insensitive driver name ({@see \e107\Database\Drivers})
 *     'prefix' => 'string', // The prefix to apply to every table in the database
 *
 *     // Options for mysql, pgsql, mssql, oracle
 *     'username' => 'string', // Login username (leave blank for none)
 *     'password' => 'string', // Login password (leave blank for none)
 *     'host' => 'string', // Hostname of the database server (domain or IP). 'localhost' is the most common value.
 *     'port' => integer,  // Port number of the database server (remove or set to null for default)
 *     'name' => 'string', // Name of the database or schema to use on the server
 *
 *     // Options for sqlite
 *     'path' => 'string', // Absolute path to the SQLite database
 *
 *     // Options for mysql, pgsql, oracle
 *     'charset' => 'string', // The character set used when connecting to the database (leave blank for default)
 *
 *     // Options for mysql
 *     'socket' => 'string', // Instead of a username and password, the path of a UNIX socket used to connect to MySQL
 * ]
 */
$database = [
    // Required options
    'driver'   => 'mysql',
    'prefix'   => '{{ mySQLprefix }}',
    // Typical options for database servers
    'host'     => '{{ mySQLserver }}',
    'port'     => 3306,
    'name'     => '{{ mySQLdefaultdb }}',
    'username' => '{{ mySQLuser }}',
    'password' => '{{ mySQLpassword }}',
    // Typical options for database files
    'path'     => null,
    // Advanced options
    'charset'  => 'utf8mb4',
    'socket'   => null,
];

/**
 * Base Paths
 *
 * Relative paths to the directories of each kind of e107 resource
 *
 * e107 will append site-specific paths to these base paths.
 *
 * @var array $paths = [
 *     // Current paths
 *     'admin'     => 'e107_admin/',      // Admin area files
 *     'core'      => 'e107_core/',       // System assets
 *     'docs'      => 'e107_docs/',       // Admin documentation
 *     'handlers'  => 'e107_handlers/',   // System backend framework
 *     'images'    => 'e107_images/',     // System images
 *     'languages' => 'e107_languages/',  // Locale files
 *     'media'     => 'e107_media/',      // Uploaded files
 *     'plugins'   => 'e107_plugins/',    // System plugins
 *     'system'    => 'e107_system/',     // Runtime-generated site files
 *     'themes'    => 'e107_themes/',     // System themes
 *     'web'       => 'e107_web/',        // Frontend web libraries
 *
 *     // Legacy paths
 *     'files'     => 'e107_files/',      // e107 v1/v2 supplementary files
 * ]
 */
$paths = [
    'admin'     => 'e107_admin/',
    'core'      => 'e107_core/',
    'docs'      => 'e107_docs/',
    'handlers'  => 'e107_handlers/',
    'images'    => 'e107_images/',
    'languages' => 'e107_languages/',
    'media'     => 'e107_media/',
    'plugins'   => 'e107_plugins/',
    'system'    => 'e107_system/',
    'themes'    => 'e107_themes/',
    'web'       => 'e107_web/',
];
