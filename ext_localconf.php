<?php

// Let's configuration of this extension from "Extension Manager"
if(isset($_EXTCONF)) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ns_timeline'] = unserialize($_EXTCONF);
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'][1792058932] = [
    'nodeName' => 'flexFormNoTabsContainer',
    'priority' => 48,
    'class' => \NITSAN\NsTimeline\Form\Resolver\PremiumStripFlexFormNoTabsContainerResolver::class,
];
