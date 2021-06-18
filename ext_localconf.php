<?php
// Let's configuration of this extension from "Extension Manager"
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ns_timeline'] = unserialize($_EXTCONF);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ns_timeline/Configuration/TsConfig/NewContentElement.tsconfig">'
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
// use same identifier as used in TSconfig for icon
$iconRegistry->registerIcon(
   // use same identifier as used in TSconfig for icon
   'ns_timeline-icon',
   	\TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
   // font-awesome identifier ('external-link-square')
   ['name' => 'external-link-square']
);

// Draw content into content elements
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['nstimeline'] = \NITSAN\NsTimeline\Hooks\NewContentElementPreviewRenderer::class;



