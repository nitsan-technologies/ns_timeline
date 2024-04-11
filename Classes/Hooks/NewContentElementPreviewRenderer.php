<?php

namespace NITSAN\NsTimeline\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Service\FlexFormService;

class NewContentElementPreviewRenderer implements PageLayoutViewDrawItemHookInterface
{
    /**
     * Preprocesses the preview rendering of a content element of type "My new content element"
     *
     * @param PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionality
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     *
     * @return void
     */
    public function preProcess(
        PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    )
    {
        $extKey = 'ns_timeline';

        if ($row['CType'] === 'nstimeline') {

            $drawItem = false;
            $headerContent = '';


            if (!empty($row['pi_flexform'])) {
                /** @var FlexFormService $flexFormService */
                if (version_compare(TYPO3_branch, '9.0', '>')) {
                    $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
                } else {
                    $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
                }
            }

            $options = [];
            $flexFormAsArray = GeneralUtility::xml2array($row['pi_flexform']);
            $mynormalVariation = $flexFormAsArray['data']['sDEF']['lDEF']['normalVariation']['vDEF'];   // Get Standard Type Values


            $view = $this->getFluidTemplate($extKey, $mynormalVariation);


            
            $maintype = $flexFormAsArray['data']['sDEF']['lDEF']['mainType']['vDEF'];   // Get MaiType Value From Custom Element


            // If Table Found Then....
            if (isset($flexFormAsArray['data']) && is_array($flexFormAsArray['data'])) {
                foreach ($flexFormAsArray['data'] as $base) {
                    if (!empty($base['lDEF']) && is_array($base['lDEF'])) {
                        foreach ($base['lDEF'] as $optionKey => $optionValue) {

                            // Check Condition For News
                            $orderingByData = isset($base['lDEF']['newsOrderBy']['vDEF']) ? $base['lDEF']['newsOrderBy']['vDEF'] : null;
                            $orderingDirectionData = isset($base['lDEF']['newsOrderDirection']['vDEF']) ? $base['lDEF']['newsOrderDirection']['vDEF'] : null;


                            $optionParts = explode('.', $optionKey);
                            $optionKey = array_pop($optionParts);
                            if (isset($optionValue['el']) && is_array($optionValue['el'])) {
                                foreach ($optionValue['el'] as $subprekey => $subArrayItem) {
                                    foreach ($subArrayItem as $subsubArrayItem) {
                                        if (isset($subsubArrayItem['el'])) {
                                            foreach ($subsubArrayItem['el'] as $subkey => $value) {

                                                // Convert Multiple Images to Array
                                                if(isset($subsubArrayItem['el']['image']['vDEF'])) {
                                                    $options['sectionimages'] = explode(',', $subsubArrayItem['el']['image']['vDEF']);
                                                }

                                                $options[$optionKey] = isset($options[$optionKey]) ? $options[$optionKey] : [];
                                                if (!is_array($options[$optionKey])) {
                                                    $options[$optionKey] = [];
                                                }

                                                $options[$optionKey][$subprekey] = isset($options[$optionKey][$subprekey]) ? $options[$optionKey][$subprekey] : [];
                                                if (!is_array($options[$optionKey][$subprekey])) {
                                                    $options[$optionKey][$subprekey] = [];
                                                }

                                                // Add Images Array to Main Array
                                                $options[$optionKey][$subprekey][$subkey] = $value['vDEF'];
                                                $options[$optionKey][$subprekey]['image'] = isset($options['sectionimages']) ? $options['sectionimages'] : '';
                                            }
                                        }
                                    }
                                }
                            } else {
                                $options[$optionKey] = $optionValue['vDEF'] === '1' ? true : $optionValue['vDEF'];
                            }

                        }
                    }
                }
            }

            // assign all to view
            $view->assignMultiple(['flexformData' => $options]);

            // return the preview
            $itemContent = $parentObject->linkEditContent($view->render(), $row);

        }
    }

    /**
     * @param string $extKey
     * @param string $templateName
     * @return string the fluid template
     */
    protected function getFluidTemplate($extKey, $mynormalVariation)
    {

        $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Templates/Backend/' . $mynormalVariation . '.html');

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($fluidTemplateFile);
        return $view;
    }
}
