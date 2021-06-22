<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_ProductFeed
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFeed\Controller\Adminhtml\ManageFeeds;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Mageplaza\ProductFeed\Controller\Adminhtml\AbstractManageFeeds;
use Mageplaza\ProductFeed\Model\FeedFactory;
use Magento\Framework\Archive\Zip;

/**
 * Class Download
 * @package Mageplaza\ProductFeed\Controller\Adminhtml\ManageFeeds
 */
class Download extends AbstractManageFeeds
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var Zip
     */
    protected $zipArchive;

    /**
     * Download constructor.
     *
     * @param FeedFactory $feedFactory
     * @param Registry $coreRegistry
     * @param Context $context
     * @param FileFactory $fileFactory
     */
    public function __construct(
        FeedFactory $feedFactory,
        Registry $coreRegistry,
        Context $context,
        FileFactory $fileFactory,
        Zip $zipArchive
    ) {
        $this->fileFactory = $fileFactory;
        $this->zipArchive  = $zipArchive;
        parent::__construct($feedFactory, $coreRegistry, $context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        $feed = $this->initFeed();
        if (!$feed->getId()) {
            $this->messageManager->addErrorMessage(__('Feed no longer exits'));

            return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
        }

        try {
          /*  return $this->fileFactory->create(
                $feed->getFileName() . '.' . $feed->getFileType(),
                [
                    'type' => 'filename',
                    'value' => 'mageplaza/feed/' .$feed->getFileName() . '.' . $feed->getFileType(),
                ],
                'media',
            );*/
            return $this->fileFactory->create(
                $feed->getFileName() .'_'. $this->randomChars() . '.zip',
                [
                    'type' => 'filename',
                    'value' => 'mageplaza/feed/' .$feed->getFileName() . '.zip',
                ],
                'media',
                'application/zip'
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Something wrong when download file: %1', $e->getMessage()));

            return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
        }
    }

    public function randomChars() {
        $str = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . 'abcdefghijklmnopqrstvwxyz' . '1234567890';
        for($i = 0; $i < 4; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
