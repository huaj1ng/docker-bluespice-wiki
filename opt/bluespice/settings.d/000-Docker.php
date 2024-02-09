<?php

$GLOBALS['wgMathoidCli'] = [
	'/usr/local/bin/mathoid-remote',
	getenv( 'FORMULA_PROTOCOL' ) ?? 'http'
	. '://'
	. ( getenv( 'FORMULA_HOST' ) ?? 'formula' )
	. ':'
	. ( getenv( 'FORMULA_PORT' ) ?? '10044' )
];