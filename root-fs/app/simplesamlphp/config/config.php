<?php

include( __DIR__ . '/config.php.dist' );

$protocol = getenv( 'WIKI_PROTOCOL' ) ?? 'http';
$host = getenv( 'WIKI_HOST' ) ?? 'localhost';
$portSuffix = getenv( 'WIKI_PORT' ) ? ':' . getenv( 'WIKI_PORT' ) : '';
if ( $protocol === 'http' && $portSuffix === ':80' ) {
	$portSuffix = '';
} elseif ( $protocol === 'https' && $portSuffix === ':443' ) {
	$portSuffix = '';
}
$baseUrl = "$protocol://$host{$portSuffix}";

// TODO calculate from environment variable
$loglevel = SimpleSAML\Logger::WARNING;

$customConfig = [
	'baseurlpath' => '/_sp',
	'application' => [
		'baseURL' => $baseUrl
	],
	'technicalcontact_name' => getenv( 'WIKI_NAME' ) ?? 'BlueSpice',
	'technicalcontact_email' => getenv( 'WIKI_EMERGENCYCONTACT' ) ?? '',
	'auth.adminpassword' => getenv( 'INTERNAL_SIMPLESAMLPHP_ADMIN_PASS' ),
	'admin.protectindexpage' => true,
	'admin.protectmetadata' => false,
	'admin.checkforupdates' => false,
	'session.cookie.secure' => true,
	'logging.level' => $loglevel,
	'enable.http_post' => true,
	'logging.handler' => 'syslog', // write to stdout
	'debug' => array(
		'saml' => false,
		'backtraces' => false,
		'validatexml' => false,
	),
	'showerrors' => false,
	'errorreporting' => false,
	'store.type' => 'sql',
	'store.sql.dsn' => 'mysql:dbname=' . ( getenv( 'DB_NAME' ) ?? 'database' ) .';host=' . getenv( 'DB_HOST' ),
	'store.sql.username' => getenv( 'DB_USER' ),
	'store.sql.password' => getenv( 'DB_PASS' ),
	'store.sql.prefix' => 'SimpleSAMLphp_' . ( getenv( 'DB_PREFIX' ) ?? '' ),
];

$config = $customConfig + $config;

unset( $customConfig );
unset( $protocol );
unset( $host );
unset( $portSuffix );
unset( $baseUrl );
