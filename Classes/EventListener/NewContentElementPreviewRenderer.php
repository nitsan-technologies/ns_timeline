<?php

declare(strict_types=1);

namespace NITSAN\NsTimeline\EventListener;

use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;

final class NewContentElementPreviewRenderer
{
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        $extKey = 'ns_timeline';
        $versionNumber =  VersionNumberUtility::convertVersionStringToArray(VersionNumberUtility::getCurrentTypo3Version());
        if ($versionNumber['version_main'] <= '13') {
            $row = $event->getRecord();
        }else{
            $row = $event->getRecord();
            $row = $row->getRawRecord()?->toArray() ?? [];
        }
       
        if ($row['CType'] === 'nstimeline') {

            $drawItem = false;
            $headerContent = '';

            if (!empty($row['pi_flexform'])) {
                /** @var FlexFormTools $FlexFormTools */
                $flexFormService = GeneralUtility::makeInstance(FlexFormTools::class);
            }

            $options = [];
            $flexFormAsArray = GeneralUtility::xml2array($row['pi_flexform']);
            $mynormalVariation = $flexFormAsArray['data']['sDEF']['lDEF']['normalVariation']['vDEF'];   // Get Standard Type Values

            if ($versionNumber['version_main'] <= '13') {
                $view = $this->getFluidTemplateOld($extKey, $mynormalVariation);
            }else{
                $view = $this->getFluidTemplatenew($extKey, $mynormalVariation);
            }

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
     * @return StandaloneView
     */
    protected function getFluidTemplateOld($extKey, $mynormalVariation)
    {
        $viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: ['EXT:ns_timeline/Resources/Private/Templates/Backend'],
        );
        $view = $viewFactory->create($viewFactoryData);
        $view->render($mynormalVariation);
        return $view;
    }

    /**
     * @param string $extKey
     * @return StandaloneView
     */
    protected function getFluidTemplateNew($extKey, $mynormalVariation)
    {
        $viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: ['EXT:ns_timeline/Resources/Private/Templates/Backend'],
        );
        $view = $viewFactory->create($viewFactoryData);
        $view->render($mynormalVariation);
        return $view;
    }
}