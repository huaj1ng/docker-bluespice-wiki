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
$GLOBALS['wgArticlePath'] = '/wiki/$1';
$GLOBALS['wgResourceBasePath'] = $GLOBALS['wgScriptPath'];
$GLOBALS['wgLogos'] = [
	'1x' => $GLOBALS['wgResourceBasePath'] . '/resources/assets/change-your-logo.svg',
	'icon' => $GLOBALS['wgResourceBasePath']. '/resources/assets/change-your-logo-icon.svg',
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
$GLOBALS['wgUploadPath'] = $GLOBALS['wgScriptPath'] . '/img_auth.php';
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
$GLOBALS['wgSMTP'] = [
	'host' => getenv( 'SMTP_HOST' ),
	'IDHost' => getenv( 'SMTP_IDHOST' ),
	'port' => getenv( 'SMTP_PORT' ) ?? 25,
	'auth' => getenv( 'SMTP_USER' ) ? true : false,
	'username' => getenv( 'SMTP_USER' ),
	'password' => getenv( 'SMTP_PASS' ),
];
$GLOBALS['wgMathoidCli'] = [
	'/usr/local/bin/mathoid-remote',
	( getenv( 'FORMULA_PROTOCOL' ) ?? 'http' )
	. '://'
	. ( getenv( 'FORMULA_HOST' ) )
	. ':'
	. ( getenv( 'FORMULA_PORT' ) ),
];

if ( getenv( 'DEV_WIKI_DEBUG' ) ) {
	#$GLOBALS['wgDebugToolbar'] = true;
	$GLOBALS['wgShowExceptionDetails'] = true;
	$GLOBALS['wgDevelopmentWarnings'] = true;
	$GLOBALS['wgDebugDumpSql'] = true;
}

if ( getenv( 'DEV_WIKI_DEBUG_LOGCHANNELS' ) ) {
	$logChannels = explode( ',', getenv( 'DEV_WIKI_DEBUG_LOGCHANNELS' ) );
	$logChannels = array_map( 'trim', $logChannels );
	foreach ( $logChannels as $channel ) {
		$GLOBALS['bsgDebugLogGroups'][$channel] = '/dev/stdout';
	}
	unset( $logChannels );
}

// Taken from `extensions/BlueSpiceWikiFarm/SimpleFarmer/src/Dispatcher.php`
// Not all of this may be required
$GLOBALS['wgUploadDirectory'] = "/data/images";
$GLOBALS['wgReadOnlyFile'] = "{$GLOBALS['wgUploadDirectory']}/lock_yBgMBwiR";
$GLOBALS['wgFileCacheDirectory'] = "{$GLOBALS['wgUploadDirectory']}/cache";
$GLOBALS['wgDeletedDirectory'] = "{$GLOBALS['wgUploadDirectory']}/deleted";
$GLOBALS['wgCacheDirectory'] = "/data/cache";

define( 'BSDATADIR', "/data/extensions/BlueSpiceFoundation/data" ); //Present
define( 'BS_DATA_DIR', "{$GLOBALS['wgUploadDirectory']}/bluespice" ); //Future
define( 'BS_CACHE_DIR', "{$GLOBALS['wgFileCacheDirectory']}/bluespice" );
define( 'BS_DATA_PATH', "{$GLOBALS['wgUploadPath']}/bluespice" );

require_once '/data/pre-init-settings.php';

require_once "$IP/LocalSettings.BlueSpice.php";

wfLoadExtension( 'BlueSpiceExtendedSearch' );
$GLOBALS['bsgOverrideESBackendHost'] = getenv( 'SEARCH_HOST' );
$GLOBALS['bsgOverrideESBackendPort'] = getenv( 'SEARCH_PORT' ) ?? '9200';
$GLOBALS['bsgOverrideESBackendTransport'] = getenv( 'SEARCH_PROTOCOL' ) ?? 'http';
$GLOBALS['bsgOverrideESBackendUser'] = getenv( 'SEARCH_USER' ) ?? '';
$GLOBALS['bsgOverrideESBackendPass'] = getenv( 'SEARCH_PASS' ) ?? '';

wfLoadExtension( 'BlueSpiceUEModulePDF' );
$GLOBALS['bsgOverrideUEModulePDFPdfServiceURL'] =
	( getenv( 'PDF_PROTOCOL' ) ?? 'http' )
	. '://'
	. ( getenv( 'PDF_HOST' ) )
	. ':'
	. ( getenv( 'PDF_PORT' ) )
	. '/BShtml2PDF';

require_once '/data/post-init-settings.php';
