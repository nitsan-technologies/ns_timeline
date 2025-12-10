<?php

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$_EXTKEY = 'ns_timeline';
$versionNumber = VersionNumberUtility::getNumericTypo3Version();

// Adds the content element to the "Type" dropdown
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:ns_timeline/Resources/Private/Language/locallang_db.xlf:wizard.ns_timeline',
        'nstimeline',
        'ns_timeline-icon',
        'default'
    ],
    'textmedia',
    'after'
);

$flexFormPath = 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/ns_timeline.xml';

if ($versionNumber != '14.0.0' && $versionNumber != '14.0.1') {
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/ns_timeline.xml',
        'nstimeline'
    );

    $GLOBALS['TCA']['tt_content']['types']['nstimeline'] = [
      'showitem' => '
            --palette--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:palette.general;general,
            --palette--;;visibility,
            --palette--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:tca.tab.elements;,pi_flexform,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,space_before_class,space_after_class,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:palette.access,
            --palette--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:palette.access;access,
        ',
    ];
}else{
    $GLOBALS['TCA']['tt_content']['types']['nstimeline'] = [
        'showitem' => '
            --palette--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:palette.general;general,
            --palette--;;visibility,
            --palette--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:tca.tab.elements;,pi_flexform,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,space_before_class,space_after_class,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:palette.access,
            --palette--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:palette.access;access,
        ',
        'columnsOverrides' => [
            'pi_flexform' => [
                'config' => [
                    'ds' => 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/ns_timeline.xml',
                ],
            ],
        ],
    ];
}

