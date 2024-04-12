<?php

namespace NITSAN\NsTimeline\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Deafult processor
 */
class DefaultProcessor implements DataProcessorInterface
{
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
        $processedData['content'] = $this->getOptionsFromFlexFormData($processedData['data']);

        return $processedData;
    }

    protected $defaultOrderings = ['crdate' => QueryInterface::ORDER_DESCENDING];

    /**
     * @param array $row
     * @return array
     */
    protected function getOptionsFromFlexFormData(array $row)
    {
        $options = [];
        $flexFormAsArray = GeneralUtility::xml2array($row['pi_flexform']);
        if (isset($flexFormAsArray['data']) && is_array($flexFormAsArray['data'])) {
            foreach ($flexFormAsArray['data'] as $base) {
                if (!empty($base['lDEF']) && is_array($base['lDEF'])) {
                    foreach ($base['lDEF'] as $optionKey => $optionValue) {
                        {
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

                                                // Add Images Array to Main Aray
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
        }

        if($options['section'] != '') {
            foreach ($options['section'] as $subdatekey => $datevalue) {
                $time[$subdatekey] = isset($datevalue['timeFrom']) ? $datevalue['timeFrom'] : '';
                $date[$subdatekey] = $datevalue['date'];
            }

            if(!empty($datevalue['date'])) {
                $date  = array_column($options['section'], 'date');
                $options['newsOrderDirection'] = isset($options['newsOrderDirection']) ? $options['newsOrderDirection'] : 'asc';
                if ($options['newsOrderDirection'] == 'asc') {
                    array_multisort($date, SORT_ASC, $options['section']);
                } else {
                    array_multisort($date, SORT_DESC, $options['section']);
                }
            } elseif(!empty($datevalue['timeFrom'])) {
                $time  = array_column($options['section'], 'timeFrom');
                if ($options['newsOrderDirection'] == 'asc') {
                    array_multisort($time, SORT_ASC, $options['section']);
                } else {
                    array_multisort($time, SORT_DESC, $options['section']);
                }
            }
        }

        return $options;
    }
}
