<?php
/**
 * Informatics
 * Informatics Specialpricelimit
 *
 * @category   Informatics
 * @package    Informatics_Specialpricelimit
 * @copyright  Copyright © 2018-2019
 */
namespace Informatics\Specialpricelimit\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface

{

    protected $eavSetup;



    public function __construct(EavSetup $eavSetup)

    {

        $this->eavSetup = $eavSetup;

    }

	

	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)

    {

		$setup->startSetup();

		$this->eavSetup->addAttribute(

			'catalog_product',

			'min_qty_special_price',

			[

                'type' => 'int',

                'backend' => '',

                'frontend' => '',

                'label' => 'Minimum Qty for Special Price',

                'input' => 'text',

                'class' => 'validate-digits',

                'source' => '',

                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,

                'visible' => true,

                'required' => false,

                'user_defined' => false,

                'searchable' => false,

                'filterable' => false,

                'comparable' => false,

                'visible_on_front' => false,

                'used_in_product_listing' => true,

                'unique' => false,

                'apply_to' => '',

                'group' => 'Advanced Pricing'

			]

		);



		$setup->endSetup();

	}

}