<?php

declare(strict_types=1);

namespace NITSAN\NsTimeline\EventListener;

use NITSAN\NsTimeline\Backend\PremiumStrip;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * Adds the premium strip to the Page module preview of the Timeline content element.
 *
 * The event stops propagation once a listener sets preview content, so NewContentElementPreviewRenderer calls
 * prependTo() for its own preview; this listener only runs when that renderer left the preview empty.
 */
final class PremiumStripPreview
{
    public function __construct(
        private readonly PremiumStrip $premiumStrip,
        private readonly PageRenderer $pageRenderer,
    ) {}

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content' || $this->getRecordType($event) !== PremiumStrip::CONTENT_TYPE) {
            return;
        }

        $content = $event->getPreviewContent() ?? '';
        $contentWithStrip = $this->prependTo($content);
        if ($contentWithStrip !== $content) {
            $event->setPreviewContent($contentWithStrip);
        }
    }

    public function prependTo(string $content): string
    {
        $strip = $this->premiumStrip->render();
        if ($strip === '') {
            return $content;
        }

        $this->pageRenderer->addCssFile(PremiumStrip::STYLESHEET);

        return $strip . $content;
    }

    /**
     * TYPO3 v13+ exposes the record type on the event, v12 only passes the record row.
     */
    private function getRecordType(PageContentPreviewRenderingEvent $event): string
    {
        if (method_exists($event, 'getRecordType')) {
            return (string)$event->getRecordType();
        }

        $record = $event->getRecord();

        return is_array($record) ? (string)($record['CType'] ?? '') : '';
    }
}
