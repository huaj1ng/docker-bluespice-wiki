<?php

// We must not set MW_CONFIG_FILE if we are running the CLI installer
// in `run-installation`, because otherwise it will not allow us to install
if (
		PHP_SAPI === 'cli'
		&& isset( $_SERVER['SCRIPT_NAME'] )
		&& basename( $_SERVER['SCRIPT_NAME'] ) === 'install.php'
	) {
	return;
}

define( 'MW_CONFIG_FILE', '/app/conf/LocalSettings.php' );