<?php

/* =Load core - do not remove
----------------------------------------------- */
require_once( TEMPLATEPATH . '/includes/init.php' );

try {
	$obj = new Cyon();
} catch (Exception $e) {
	echo $e->getMessage(), "\n";
}
