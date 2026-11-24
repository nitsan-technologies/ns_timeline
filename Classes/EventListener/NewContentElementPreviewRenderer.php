<?php

declare(strict_types=1);

namespace NITSAN\NsTimeline\EventListener;

use NITSAN\NsTimeline\DataProcessing\DefaultProcessor;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

final class NewContentElementPreviewRenderer
{
    public function __construct(
        private readonly PremiumStripPreview $premiumStripPreview,
    ) {}

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        $extKey = 'ns_timeline';
        $versionNumber = VersionNumberUtility::convertVersionStringToArray(VersionNumberUtility::getCurrentTypo3Version());
        if ($versionNumber['version_main'] <= '13') {
            $row = $event->getRecord();
        } else {
            $row = $event->getRecord();
            $row = $row->getRawRecord()?->toArray() ?? [];
        }

        if (($row['CType'] ?? '') !== 'nstimeline' || empty($row['pi_flexform'])) {
            return;
        }

        $options = DefaultProcessor::parseFlexForm($row['pi_flexform']);
        $mynormalVariation = DefaultProcessor::sanitizeStyleVariation($options['normalVariation'] ?? '');

        // None / unknown: keep previous "no style preview" behaviour, still show premium strip.
        if ($mynormalVariation === '') {
            $event->setPreviewContent($this->premiumStripPreview->prependTo(''));
            return;
        }

        if ($versionNumber['version_main'] <= '13') {
            $view = $this->getFluidTemplateOld($extKey, $mynormalVariation);
            $view->assignMultiple(['flexformData' => $options]);
            $previewHtml = $view->render();
        } else {
            $view = $this->getFluidTemplateNew();
            $view->assignMultiple(['flexformData' => $options]);
            $previewHtml = $view->render($mynormalVariation);
        }

        $event->setPreviewContent($this->premiumStripPreview->prependTo($previewHtml));
    }

    /**
     * @param string $extKey
     * @param string $mynormalVariation Allow-listed style key (style-1|style-2)
     */
    protected function getFluidTemplateOld($extKey, $mynormalVariation): StandaloneView
    {
        $fluidTemplateFile = GeneralUtility::getFileAbsFileName(
            'EXT:' . $extKey . '/Resources/Private/Templates/Backend/' . $mynormalVariation . '.html'
        );

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($fluidTemplateFile);
        return $view;
    }

    protected function getFluidTemplateNew(): ViewInterface
    {
        $viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: ['EXT:ns_timeline/Resources/Private/Templates/Backend'],
        );

        return $viewFactory->create($viewFactoryData);
    }
}
