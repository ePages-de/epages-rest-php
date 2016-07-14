<?php
namespace ep6;
/**
 * The Product Availibility 'enum'.
 *
 * This are the possible Product Availibilities.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.2.0
 * @subpackage Shobojects\Product
 */
abstract class ProductAvailibility {
	/** @var String The product is on stock. **/
	const ONSTOCK = "OnStock";
	/** @var String The product is on stock but stock level is nearly empty. **/
	const WARNSTOCK = "WarnStock";
	/** @var String The product is out of stock. **/
	const OUTSTOCK = "OutStock";
}
?>