<?php
namespace Mageplaza\GiftCard\Block\Adminhtml\Code;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry;

    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'giftcard_id';
        $this->_blockGroup = 'Mageplaza_GiftCard';
        $this->_controller = 'adminhtml_code';
        parent::_construct();
        $id = $this->getRequest()->getParam('id');
        $this->addButton(
            'save_and_continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            1
        );

        if (!empty($id)) {
            $this->addButton(
                'delete',
                [
                    'label' => __('Delete'),
                    'class' => 'delete',
                    'onclick' => 'deleteConfirm(\'' . __(
                            'Are you sure you want to do this?'
                        ) . '\', \'' . $this->getUrl('*/*/delete', [
                            'id' => (int)$this->getRequest()->getParam('id')]) . '\', {data: {}})'
                ]
            );
        } else {
            $this->addButton(
                'save',
                [
                    'label' => __('Save Gift Card'),
                    'class' => 'save primary',
                    'data_attribute' => [
                        'mage-init' => ['button' => ['event' => 'save', 'target' => '#edit_form']],
                    ]
                ],
                1
            );
        }
    }

}
