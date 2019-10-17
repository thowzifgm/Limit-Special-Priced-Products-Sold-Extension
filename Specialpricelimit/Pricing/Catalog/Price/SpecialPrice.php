<?php
/**
 * Informatics
 * Informatics Specialpricelimit
 *
 * @category   Informatics
 * @package    Informatics_Specialpricelimit
 * @copyright  Copyright © 2018-2019
 */
namespace Informatics\Specialpricelimit\Pricing\Catalog\Price;

use Magento\Framework\App\ObjectManager;

/**
 * Special price model
 */

class SpecialPrice extends \Magento\Catalog\Pricing\Price\SpecialPrice

{

    /**
     * @return bool|float
     */

    public function getValue()

    {

        if (null === $this->value) {            



            $this->value = false;

            $specialPrice = $this->getSpecialPrice();

            if ($specialPrice !== null && $specialPrice !== false && $this->isScopeDateInInterval()) {

                $this->value = (float) $specialPrice;

            }


            if($this->_isSpecialPriceAllowedBasedOnMinQty())

                $this->value = (float) $this->product->getPrice();

        }

//echo $this->value . ",<,"; exit;

        return $this->value;

    }



    /**
     * @return bool
     */

    protected function _isSpecialPriceAllowedBasedOnMinQty()

    {

        $minQtyForSpecialPrice = $this->product->getMinQtySpecialPrice();//echo $minQtyForSpecialPrice . " 1 <br />";



        if(isset($minQtyForSpecialPrice) && $minQtyForSpecialPrice != "")

        {

            // get Stock State Object

            $stockState = ObjectManager::getInstance()->get('\Magento\CatalogInventory\Api\StockStateInterface');



            // get Current Stock Qty

            $stockQty = $stockState->getStockQty($this->product->getId(), $this->product->getStore()->getWebsiteId());

            //echo $stockQty . " 2 <br />";

            // check Min Qty with Stock Qty

            $minQtyForSpecialPrice = (int) $minQtyForSpecialPrice;// echo $minQtyForSpecialPrice . " 3 <br />";

            if($minQtyForSpecialPrice != 0 && $minQtyForSpecialPrice >= $stockQty)

                return true;

        }



        return false;

    }

}