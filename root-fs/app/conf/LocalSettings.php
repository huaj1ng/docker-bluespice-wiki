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
if ( getenv( 'EDITION' === "farm") ) {
	$GLOBALS['wgArticlePath'] = "/wiki/$1";
	if( FARMER_IS_ROOT_WIKI_CALL === false ) {
        $GLOBALS['wgArticlePath'] = "/" . FARMER_CALLED_INSTANCE . "/wiki/$1";
	}
}
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
$GLOBALS['wgMainCacheType'] = CACHE_ACCEL;
$GLOBALS['wgMemCachedServers'] = ["cache:11211"];
$GLOBALS['wgMainCacheType'] = CACHE_MEMCACHED;
$GLOBALS['wgMessageCacheType'] = CACHE_ACCEL;
$GLOBALS['wgLocalisationCacheConf']['store'] = 'array';
$GLOBALS['wgLocalisationCacheConf']['storeDirectory'] = "/tmp/";
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
$GLOBALS['wgUploadDirectory'] = "/data/bluespice/images";
$GLOBALS['wgReadOnlyFile'] = "{$GLOBALS['wgUploadDirectory']}/lock_yBgMBwiR";
$GLOBALS['wgFileCacheDirectory'] = "{$GLOBALS['wgUploadDirectory']}/cache";
$GLOBALS['wgDeletedDirectory'] = "{$GLOBALS['wgUploadDirectory']}/deleted";
$GLOBALS['wgCacheDirectory'] = "/data/bluespice/cache";

define( 'BSDATADIR', "/data/bluespice/extensions/BlueSpiceFoundation/data" ); //Present
define( 'BS_DATA_DIR', "{$GLOBALS['wgUploadDirectory']}/bluespice" ); //Future
define( 'BS_CACHE_DIR', "{$GLOBALS['wgFileCacheDirectory']}/bluespice" );
define( 'BS_DATA_PATH', "{$GLOBALS['wgUploadPath']}/bluespice" );

$GLOBALS['bsgSimpleFarmer_instanceDirectory'] = '/data/bluespice/_sf_instances/';
$GLOBALS['bsgSimpleFarmer_archiveDirectory'] = '/data/bluespice/_sf_archives/';

require_once '/data/bluespice/pre-init-settings.php';
if ( getenv( 'EDITION' === "farm") ) {
	require_once "$IP/extensions/BlueSpiceWikiFarm/BlueSpiceWikiFarm.php";
}
else {	
	require_once "$IP/LocalSettings.BlueSpice.php";
}
wfLoadExtension( 'BlueSpiceExtendedSearch' );
$GLOBALS['bsgESBackendHost'] = 'search';
$GLOBALS['bsgESBackendPort'] = '9200';
$GLOBALS['bsgESBackendTransport'] = 'http';

wfLoadExtension( 'BlueSpiceUEModulePDF' );
$GLOBALS['bsgOverrideUEModulePDFPdfServiceURL'] ='http://pdf:8080/BShtml2PDF';

wfLoadExtension( 'PdfHandler' );
$GLOBALS['wgPdfProcessor'] = '/usr/bin/gs';
$GLOBALS['wgPdfPostProcessor'] = $GLOBALS['wgImageMagickConvertCommand'];
$GLOBALS['wgPdfInfo'] = '/usr/bin/pdfinfo';
$GLOBALS['wgPdftoText'] = '/usr/bin/pdftotext';


$GLOBALS['wgDrawioEditorBackendUrl'] = "$wgServer/_diagram/";

### Dynamic assembly of $GLOBALS['wgMathoidCli']
$GLOBALS['wgMathoidCli'] = [
	'/app/bin/mathoid-remote',
	'http://formula:10044'
];

require_once '/data/bluespice/post-init-settings.php';
