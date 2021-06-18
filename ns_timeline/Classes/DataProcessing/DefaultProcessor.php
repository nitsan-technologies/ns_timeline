<?php

namespace NITSAN\NsTimeline\DataProcessing;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Core\Messaging\FlashMessageService;

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

    protected $defaultOrderings = ['crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING];

    /**
     * @param array $row
     * @return array
     */
    protected function getOptionsFromFlexFormData(array $row)
    {
        $options = [];
        $flexFormAsArray = GeneralUtility::xml2array($row['pi_flexform']);
        
        $queryImage = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');

        // Find tx_news_domain_model_news table from database
        $resultTable = $queryImage
                ->count("*")
                ->from("information_schema.tables")
                ->where(
                      $queryImage->expr()->eq('table_name', $queryImage->createNamedParameter('tx_news_domain_model_news'))
                   )
                ->execute();
        $ansTable = $resultTable->fetch();
        $ansTableNew = $ansTable['COUNT(*)'];   // Count Value For Table. If Table Found Then Get 1 Otherwise 0
        $maintype = $flexFormAsArray['data']['sDEF']['lDEF']['mainType']['vDEF'];   // Get MaiType Value From Custom Element

        // Check If Table is Found For News Extensions or Type = News
        if($ansTableNew == '0' && $maintype == '2')
        {
            $message = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessage::class,
                'Please Install news & md_news_author Extensions',
                'Error : Installation Remaining', // the header
                \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR, // Message Type
                true 
            );
        }
        else{
            if (isset($flexFormAsArray['data']) && is_array($flexFormAsArray['data'])) {
                foreach ($flexFormAsArray['data'] as $base) {
                    if (!empty($base['lDEF']) && is_array($base['lDEF'])) {
                        foreach ($base['lDEF'] as $optionKey => $optionValue) {
                            
                            // Check Condition For News
                            $orderingByData = $base['lDEF']['newsOrderBy']['vDEF'];
                            $orderingDirectionData = $base['lDEF']['newsOrderDirection']['vDEF'];
                    
                            if($optionKey == 'startingpoint')
                            {
                                // get News Uid Listing From Storage Pid
                                $storage_id = $optionValue['vDEF'];
                                if(!empty($storage_id))
                                {
                                    $queryImage = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
                                    if(!empty($orderingByData) && !empty($orderingDirectionData))
                                    {
                                        $resultImage = $queryImage
                                            ->select('tx_news_domain_model_news.uid','pid')
                                            ->from('tx_news_domain_model_news')
                                            ->where(
                                                    $queryImage->expr()->in('pid', $storage_id)
                                                )
                                            ->orderBy($orderingByData,$orderingDirectionData)
                                            ->execute();
                                    }
                                    else{
                                        $resultImage = $queryImage
                                            ->select('tx_news_domain_model_news.uid','pid')
                                            ->from('tx_news_domain_model_news')
                                            ->where(
                                                    $queryImage->expr()->in('pid', $storage_id)
                                                )
                                            ->execute();
                                    }
                                    while ($row = $resultImage->fetch()) 
                                    {
                                        // initialize ObjectManager
                                        $this->objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

                                        //Call News Repository From News Extensions
                                        $newsRepository = $this->objectManager->get('GeorgRinger\News\Domain\Repository\NewsRepository');
                                        $allData = $newsRepository->findByUid($row); // Get All News Data By Uid
                                        $options['newsdata'][] = $allData;    // Create Array For All News Data
                                    }
                                }
                            }
                            else
                            {
                                $optionParts = explode('.', $optionKey);
                                $optionKey = array_pop($optionParts);
                                if (isset($optionValue['el']) && is_array($optionValue['el'])) {
                                    foreach ($optionValue['el'] as $subprekey => $subArrayItem) {
                                        foreach ($subArrayItem as $subsubArrayItem) {
                                            if (isset($subsubArrayItem['el'])) {
                                                foreach ($subsubArrayItem['el'] as $subkey => $value) {

                                                    // Convert Multiple Images to Array
                                                    $options['sectionimages'] = explode(',', $subsubArrayItem['el']['image']['vDEF']);

                                                    if (!is_array($options[$optionKey])) {
                                                        $options[$optionKey] = [];
                                                    }

                                                    if (!is_array($options[$optionKey][$subprekey])) {
                                                        $options[$optionKey][$subprekey] = [];
                                                    }
                                                    
                                                    // Add Images Array to Main Aray
                                                    $options[$optionKey][$subprekey][$subkey] = $value['vDEF'];
                                                    $options[$optionKey][$subprekey]['image'] = $options['sectionimages'];
                                                    
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
                        
            // Apply Sorting Based On Date....
            if($options['mainType'] != '2' || $options['mainType'] == 'TRUE' && $options['section'] != '')
            {
                if($options['section'] != '')
                {   
                    foreach ($options['section'] as $subdatekey => $datevalue) 
                    {
                        $time[$subdatekey] = $datevalue['timeFrom'];
                        $date[$subdatekey] = $datevalue['date'];
                    }

                    if(!empty($datevalue['date'])){
                        $date  = array_column($options['section'], 'date');
                        if ($options['newsOrderDirection'] == 'asc') {
                            array_multisort($date, SORT_ASC, $options['section']);
                        }
                        else{
                            array_multisort($date, SORT_DESC, $options['section']);   
                        }
                    }
                    else if(!empty($datevalue['timeFrom'])){
                        $time  = array_column($options['section'], 'timeFrom');
                        if ($options['newsOrderDirection'] == 'asc') {
                            array_multisort($time, SORT_ASC, $options['section']);
                        }
                        else{
                            array_multisort($time, SORT_DESC, $options['section']);
                        }
                    }
                }    
            }

            return $options;
        }
    }
}
