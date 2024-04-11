<?php

use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider;

// Let's configuration of this extension from "Extension Manager"
if(isset($_EXTCONF)){
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ns_timeline'] = unserialize($_EXTCONF);
}

ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ns_timeline/Configuration/TsConfig/NewContentElement.tsconfig">'
);

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
// use same identifier as used in TSconfig for icon
$iconRegistry->registerIcon(
    'ns_timeline-icon',
   	FontawesomeIconProvider::class,
   
   ['name' => 'external-link-square']
);

// Draw content into content elements
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['nstimeline'] = \NITSAN\NsTimeline\Hooks\NewContentElementPreviewRenderer::class;



