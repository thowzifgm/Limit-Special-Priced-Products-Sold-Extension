<?php
/**
 * Informatics
 * Informatics Specialpricelimit
 *
 * @category   Informatics
 * @package    Informatics_Specialpricelimit
 * @copyright  Copyright © 2018-2019
 */
namespace Informatics\Specialpricelimit\Model\Catalog\Product\Type;

use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Product type price model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */

class Price extends \Magento\Catalog\Model\Product\Type\Price

{

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */

    protected $_stockState;



    /**
     * Price constructor.
     * @param \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param PriceCurrencyInterface $priceCurrency
     * @param GroupManagementInterface $groupManagement
     * @param \Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory $tierPriceFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */

    public function __construct(

        \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory,

        \Magento\Store\Model\StoreManagerInterface $storeManager,

        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,

        \Magento\Customer\Model\Session $customerSession,

        \Magento\Framework\Event\ManagerInterface $eventManager,

        PriceCurrencyInterface $priceCurrency,

        GroupManagementInterface $groupManagement,

        \Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory $tierPriceFactory,

        \Magento\Framework\App\Config\ScopeConfigInterface $config,

        \Magento\CatalogInventory\Api\StockStateInterface $stockState

    ) {

        $this->_stockState = $stockState;



        parent::__construct($ruleFactory, $storeManager, $localeDate, $customerSession, $eventManager, $priceCurrency, $groupManagement, $tierPriceFactory, $config);

    }



    /**
     * Get base price with apply Group, Tier, Special prises
     *
     * @param Product $product
     * @param float|null $qty
     *
     * @return float
     */

    public function getBasePrice($product, $qty = null)

    {

        $price = (float) $product->getPrice();



        $specialPrice = $this->_applySpecialPrice($product, $price);



        if($this->_isSpecialPriceAllowedBasedOnMinQty($product))

            $specialPrice = $price;



        return min(

            $this->_applyTierPrice($product, $qty, $price),

            $specialPrice

        );

    }



    /**
     * @param Product $product
     *
     * @return bool
     */

    protected function _isSpecialPriceAllowedBasedOnMinQty($product)

    {

        $minQtyForSpecialPrice = $product->getMinQtySpecialPrice();

        if(isset($minQtyForSpecialPrice) && $minQtyForSpecialPrice != "")

        {

            // get Current Stock Qty

            $stockQty = $this->_stockState->getStockQty($product->getId(), $product->getStore()->getWebsiteId());

            // check Min Qty with Stock Qty

            $minQtyForSpecialPrice = (int) $minQtyForSpecialPrice;

            if($minQtyForSpecialPrice != 0 && $minQtyForSpecialPrice >= $stockQty)

                return true;

        }



        return false;

    }

}