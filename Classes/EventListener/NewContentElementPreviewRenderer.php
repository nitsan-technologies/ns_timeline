<?php

declare(strict_types=1);

namespace NITSAN\NsTimeline\EventListener;

use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;

final class NewContentElementPreviewRenderer
{
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        $extKey = 'ns_timeline';
        $row = $event->getRecord();
        if ($row['CType'] === 'nstimeline') {

            $drawItem = false;
            $headerContent = '';

            if (!empty($row['pi_flexform'])) {
                /** @var FlexFormService $flexFormService */
                $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
            }

            $options = [];
            $flexFormAsArray = GeneralUtility::xml2array($row['pi_flexform']);
            $mynormalVariation = $flexFormAsArray['data']['sDEF']['lDEF']['normalVariation']['vDEF'];   // Get Standard Type Values

            $view = $this->getFluidTemplate($extKey, $mynormalVariation);

            // If Table Found Then....
            if (isset($flexFormAsArray['data']) && is_array($flexFormAsArray['data'])) {
                foreach ($flexFormAsArray['data'] as $base) {
                    if (!empty($base['lDEF']) && is_array($base['lDEF'])) {
                        foreach ($base['lDEF'] as $optionKey => $optionValue) {


                            $optionParts = GeneralUtility::trimExplode('.', $optionKey);
                            $optionKey = array_pop($optionParts);
                            if (isset($optionValue['el']) && is_array($optionValue['el'])) {
                                foreach ($optionValue['el'] as $subprekey => $subArrayItem) {
                                    foreach ($subArrayItem as $subsubArrayItem) {
                                        if (isset($subsubArrayItem['el'])) {
                                            foreach ($subsubArrayItem['el'] as $subkey => $value) {

                                                // Convert Multiple Images to Array
                                                if (isset($subsubArrayItem['el']['image']['vDEF'])) {
                                                    $options['sectionimages'] = GeneralUtility::trimExplode(',', $subsubArrayItem['el']['image']['vDEF']);
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
            $event->setPreviewContent($view->render());


        }
    }

    /**
     * @param string $extKey
     * @param string $templateName
     */
    protected function getFluidTemplate($extKey, $mynormalVariation)
    {
        $fluidTemplateFile = [];

        $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Templates/Backend/' . $mynormalVariation . '.html');

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($fluidTemplateFile);
        return $view;
    }
}
