<?php

// Let's configuration of this extension from "Extension Manager"
if(isset($_EXTCONF)) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ns_timeline'] = unserialize($_EXTCONF);
}
