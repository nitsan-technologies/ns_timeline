<?php

namespace NITSAN\NsTimeline\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;

/**
 * Deafult processor
 */
class DefaultProcessor implements DataProcessorInterface
{
    /**
     * Allow-listed free style keys used as Fluid template / partial names.
     */
    public const ALLOWED_STYLES = ['style-1', 'style-2'];

    /**
     * Process data
     *
     * @param ContentObjectRenderer $cObj The content object renderer, which contains data of the content element
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     * @throws ContentRenderingException
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $processedData['content'] = self::parseFlexForm($processedData['data']['pi_flexform'] ?? null);

        return $processedData;
    }

    protected $defaultOrderings = ['crdate' => QueryInterface::ORDER_DESCENDING];

    /**
     * Returns a known style key, or '' for None / unknown values (same as UI "None").
     */
    public static function sanitizeStyleVariation(mixed $value): string
    {
        $value = is_string($value) ? $value : '';

        return in_array($value, self::ALLOWED_STYLES, true) ? $value : '';
    }

    /**
     * Parses ns_timeline FlexForm XML into template options.
     *
     * Supports both legacy xml2array shapes (`section.el.item…`) and current Core
     * output where section items are already keyed directly under the field.
     */
    public static function parseFlexForm(?string $piFlexform): array
    {
        $options = [];
        if ($piFlexform === null || $piFlexform === '') {
            return $options;
        }

        $flexFormAsArray = GeneralUtility::xml2array($piFlexform);
        if (!is_array($flexFormAsArray) || !isset($flexFormAsArray['data']) || !is_array($flexFormAsArray['data'])) {
            return $options;
        }

        foreach ($flexFormAsArray['data'] as $base) {
            if (empty($base['lDEF']) || !is_array($base['lDEF'])) {
                continue;
            }
            foreach ($base['lDEF'] as $optionKey => $optionValue) {
                $optionParts = GeneralUtility::trimExplode('.', (string)$optionKey);
                $optionKey = (string)array_pop($optionParts);
                if (!is_array($optionValue)) {
                    continue;
                }

                $sectionElements = self::resolveSectionElements($optionValue);
                if ($sectionElements !== null) {
                    self::parseSectionElements($options, $optionKey, $sectionElements);
                    continue;
                }

                $vDef = $optionValue['vDEF'] ?? '';
                $options[$optionKey] = $vDef === '1' ? true : $vDef;
            }
        }

        if (array_key_exists('normalVariation', $options)) {
            $options['normalVariation'] = self::sanitizeStyleVariation($options['normalVariation']);
        }

        if (!empty($options['section']) && is_array($options['section'])) {
            self::sortSectionItems($options);
        }

        return $options;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected static function resolveSectionElements(array $optionValue): ?array
    {
        if (isset($optionValue['el']) && is_array($optionValue['el'])) {
            return $optionValue['el'];
        }

        // Current Core xml2array: <el index="item1"> becomes direct keys, no "el" wrapper.
        if (!array_key_exists('vDEF', $optionValue)) {
            return $optionValue;
        }

        return null;
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $sectionElements
     */
    protected static function parseSectionElements(array &$options, string $optionKey, array $sectionElements): void
    {
        foreach ($sectionElements as $subprekey => $subArrayItem) {
            if (!is_array($subArrayItem)) {
                continue;
            }
            foreach ($subArrayItem as $subsubArrayItem) {
                if (!is_array($subsubArrayItem) || !isset($subsubArrayItem['el']) || !is_array($subsubArrayItem['el'])) {
                    continue;
                }
                foreach ($subsubArrayItem['el'] as $subkey => $value) {
                    if (!is_array($value)) {
                        continue;
                    }
                    if (isset($subsubArrayItem['el']['image']['vDEF'])) {
                        $options['sectionimages'] = GeneralUtility::trimExplode(',', (string)$subsubArrayItem['el']['image']['vDEF']);
                    }

                    if (!isset($options[$optionKey]) || !is_array($options[$optionKey])) {
                        $options[$optionKey] = [];
                    }
                    if (!isset($options[$optionKey][$subprekey]) || !is_array($options[$optionKey][$subprekey])) {
                        $options[$optionKey][$subprekey] = [];
                    }

                    $options[$optionKey][$subprekey][$subkey] = $value['vDEF'] ?? '';
                    $options[$optionKey][$subprekey]['image'] = $options['sectionimages'] ?? '';
                }
            }
        }
    }

    /**
     * @param array<string, mixed> $options
     */
    protected static function sortSectionItems(array &$options): void
    {
        $date = [];
        $time = [];
        foreach ($options['section'] as $subdatekey => $datevalue) {
            if (!is_array($datevalue)) {
                continue;
            }
            $time[$subdatekey] = $datevalue['timeFrom'] ?? '';
            $date[$subdatekey] = $datevalue['date'] ?? '';
        }

        $options['newsOrderDirection'] = $options['newsOrderDirection'] ?? 'asc';
        $direction = ($options['newsOrderDirection'] === 'asc') ? SORT_ASC : SORT_DESC;

        $hasDate = false;
        foreach ($date as $value) {
            if ($value !== '' && $value !== null) {
                $hasDate = true;
                break;
            }
        }
        $hasTime = false;
        foreach ($time as $value) {
            if ($value !== '' && $value !== null) {
                $hasTime = true;
                break;
            }
        }

        if ($hasDate) {
            $sortKeys = array_column($options['section'], 'date');
            array_multisort($sortKeys, $direction, $options['section']);
        } elseif ($hasTime) {
            $sortKeys = array_column($options['section'], 'timeFrom');
            array_multisort($sortKeys, $direction, $options['section']);
        }
    }
}
