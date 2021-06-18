<?php

namespace NITSAN\NsTimeline\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Messaging\FlashMessageService;

class NewContentElementPreviewRenderer implements PageLayoutViewDrawItemHookInterface
{
    /**
     * Preprocesses the preview rendering of a content element of type "My new content element"
     *
     * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
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
    ) {
        $extKey = 'ns_timeline';
        
        if ($row['CType'] === 'nstimeline') {

            $drawItem = false;
            $headerContent = '';

            // template
            //$view = $this->getFluidTemplate($extKey, 'NsTimeline');

            if (!empty($row['pi_flexform'])) {
                 /** @var FlexFormService $flexFormService */
                 if (version_compare(TYPO3_branch, '9.0', '>')) {
                     $flexFormService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\FlexFormService::class);
                 } else {
                     $flexFormService = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Service\FlexFormService::class);
                 } 
            }

            $options = [];
            $flexFormAsArray = GeneralUtility::xml2array($row['pi_flexform']);
            $chkmaintype = $flexFormAsArray['data']['sDEF']['lDEF']['mainType']['vDEF'];   // Get Maintype Value
            $mynormalVariation = $flexFormAsArray['data']['sDEF']['lDEF']['normalVariation']['vDEF'];   // Get Standard Type Values
            $myeventVariation = $flexFormAsArray['data']['sDEF']['lDEF']['eventVariation']['vDEF'];   // Get Event Type Values 
            $mynewsVariation = $flexFormAsArray['data']['sDEF']['lDEF']['newsVariation']['vDEF'];   // Get News Type Values

            $view = $this->getFluidTemplate($extKey, 'NsTimeline', $chkmaintype, $mynormalVariation, $myeventVariation, $mynewsVariation);
            
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
                $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                $messageQueue->addMessage($message);
            }
            else{
                // If Table Found Then....
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
                                        $queryImageNew = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
                                        $resultImage = $queryImageNew
                                            ->select('tx_news_domain_model_news.uid','pid')
                                            ->from('tx_news_domain_model_news')
                                            ->where(
                                                    $queryImage->expr()->in('pid', $storage_id)
                                                )
                                            ->execute();

                                        while($rows = $resultImage->fetch()) 
                                        {
                                            // initialize ObjectManager
                                            $this->objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

                                            //Call News Repository From News Extensions
                                            $newsRepository = $this->objectManager->get('GeorgRinger\News\Domain\Repository\NewsRepository');
                                            $allData = $newsRepository->findByUid($rows); // Get All News Data By Uid

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
                                                        
                                                        // Add Images Array to Main Array
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

                // assign all to view
                $view->assignMultiple(['flexformData' => $options]);

                // return the preview
                $itemContent = $parentObject->linkEditContent($view->render(), $row);
            } 
        }
    }

    /**
     * @param string $extKey
     * @param string $templateName
     * @return string the fluid template
     */
    protected function getFluidTemplate($extKey, $templateName, $chkmaintype, $mynormalVariation, $myeventVariation, $mynewsVariation)
    {
        // Call Standard Variation style 1 to 7
        if($chkmaintype == '0' && $mynormalVariation != '')
        {
            $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Templates/Backend/' . $mynormalVariation . '.html');            
        }
        // Call Event Variation style 8 to 10
        if($chkmaintype == '1' && $myeventVariation != '')
        {
            $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Templates/Backend/' . $myeventVariation . '.html');            
        }
        // Call News Variation style 11 to 19
        if($chkmaintype == '2' && $mynewsVariation != '')
        {
            $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Templates/Backend/' . $mynewsVariation . '.html');            
        }

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($fluidTemplateFile);
        return $view;
    }
}