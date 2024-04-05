<?php
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

### Dynamic assembly of $GLOBALS['wgServer']
$protocol = getenv( 'WIKI_PROTOCOL' ) ?? 'http';
$host = getenv( 'WIKI_HOST' ) ?? 'localhost';
$portSuffix = getenv( 'WIKI_PORT' ) ? ':' . getenv( 'WIKI_PORT' ) : '';
if ( $protocol === 'http' && $portSuffix === ':80' ) {
	$portSuffix = '';
} elseif ( $protocol === 'https' && $portSuffix === ':443' ) {
	$portSuffix = '';
}
$GLOBALS['wgServer'] = "$protocol://$host{$portSuffix}";
unset( $protocol );
unset( $host );
unset( $portSuffix );
### end

$GLOBALS['wgSitename'] = getenv( 'WIKI_NAME' ) ?? 'BlueSpice';
$GLOBALS['wgScriptPath'] = "/w";
$GLOBALS['wgResourceBasePath'] = $GLOBALS['wgScriptPath'];
$GLOBALS['wgLogos'] = [
	'1x' => $GLOBALS['$wgResourceBasePath'] . '/resources/assets/change-your-logo.svg',
	'icon' => $GLOBALS['$wgResourceBasePath']. '/resources/assets/change-your-logo-icon.svg',
];
$GLOBALS['wgEmergencyContact'] = getenv( 'WIKI_EMERGENCYCONTACT' ) ?? '';
$GLOBALS['wgPasswordSender'] = getenv( 'WIKI_PASSWORDSENDER' ) ?? '';
$GLOBALS['wgDBtype'] = "mysql";
$GLOBALS['wgDBserver'] = getenv( 'DB_HOST' ) ?? "database";
$GLOBALS['wgDBname'] = getenv( 'DB_NAME' );
$GLOBALS['wgDBuser'] = getenv( 'DB_USER' );
$GLOBALS['wgDBpassword'] = getenv( 'DB_PASS' );
$GLOBALS['wgDBprefix'] = getenv( 'DB_PREFIX' ) ?? '';
$GLOBALS['wgDBTableOptions'] = "ENGINE=InnoDB, DEFAULT CHARSET=binary";
$GLOBALS['wgSharedTables'][] = "actor";
$GLOBALS['wgMainCacheType'] = CACHE_DB;
$GLOBALS['wgMemCachedServers'] = [
	( getenv( 'MEMCACHED_HOST' ) ?? 'cache' )
	. ':'
	. ( getenv( 'MEMCACHED_PORT' ) ?? '11211' )
];
$GLOBALS['wgEnableUploads'] = true;
$GLOBALS['wgUseImageMagick'] = true;
$GLOBALS['wgImageMagickConvertCommand'] = "/usr/bin/convert";
$GLOBALS['wgLanguageCode'] = getenv( 'WIKI_LANG' ) ?? "en";
$GLOBALS['wgLocaltimezone'] = "UTC";
$GLOBALS['wgSecretKey'] = getenv( 'INTERNAL_WIKI_SECRETKEY' );
$GLOBALS['wgAuthenticationTokenVersion'] = "1";
$GLOBALS['wgUpgradeKey'] = getenv( 'INTERNAL_WIKI_UPGRADEKEY' );
$GLOBALS['wgRightsPage'] = "";
$GLOBALS['wgRightsUrl'] = "";
$GLOBALS['wgRightsText'] = "";
$GLOBALS['wgRightsIcon'] = "";
$GLOBALS['wgMetaNamespace'] = "Site";
$GLOBALS['wgPhpCli'] = '/bin/php';

require_once '/data/pre-init-settings.php';
require_once "$IP/LocalSettings.BlueSpice.php";
require_once '/data/post-init-settings.php';
