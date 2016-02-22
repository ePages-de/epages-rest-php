<?php
require("src/Shop.class.php");
ep6\Logger::setLogLevel(ep6\LogLevel::NONE);
$shop = new ep6\Shop("xxx", "xxx", "xxx", true);

# parse parameter
if (isset($_GET["product"]) && isset($_GET["stocklevel"])) {
	$product = new ep6\Product(array("productId" => $_GET["product"]));
	if ($_GET["stocklevel"] == "plus") {
		$product->increaseStockLevel();
		header("Location: ?");
	}
	else if ($_GET["stocklevel"] == "minus") {
		$product->decreaseStockLevel();
		header("Location: ?");
	}
}
if (isset($_GET["sort"])) {
	
	switch($_GET["sort"]) {
		case "priceu":
			$sort = "price";
			$direction = "asc";
			break;
		case "priced":
			$sort = "price";
			$direction = "desc";
			break;
		case "nameu":
			$sort = "name";
			$direction = "asc";
			break;
		case "named":
			$sort = "name";
			$direction = "desc";
	}
}

if (isset($_GET["count"]) && ep6\InputValidator::isRangedInt((int) $_GET["count"], 10, 100)) {
	$resultsPerPage = (int) $_GET["count"];
}

# get all products
$productFilter = new ep6\ProductFilter();
if (isset($sort)) $productFilter->setSort($sort);
if (isset($direction)) $productFilter->setDirection($direction);
if (isset($resultsPerPage)) $productFilter->setResultsPerPage($resultsPerPage);
$products = $productFilter->getProducts();
?>
<html>
<head>
<title>The Product overview</title>
</head>
<body>
<h1>The Product overview</h1>
<p>
<strong>Number of Products: <a href="?count=10">10</a>, <a href="?count=50">50</a>, <a href="?count=100">100</a></strong>
</p>
<table>
	<thead>
		<tr>
			<th>Image</th>
			<th><nobr>Name <a href="?sort=nameu">&uArr;</a> / <a href="?sort=named">&dArr;</a></nobr></th>
			<th>Description</th>
			<th><nobr>Price <a href="?sort=priceu">&uArr;</a> / <a href="?sort=priced">&dArr;</a></nobr></th>
			<th>Attributes</th>
			<th>StockLevel</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($products as $product) {
		?>
		<tr>
			<td>
				<img src="
				<?php
				echo $product->getSmallImage()->getOriginURL();
				?>
				"/>
			</td>
			<td><nobr>
				<?php
				echo $product->getName();
				?>
				</nobr>
			</td>
			<td>
				<?php
				echo $product->getDescription();
				?>
			</td>
			<td>
				<nobr>
				<?php
				echo $product->getPrice()->getFormatted()
					. " / "
					. $product->getPrice()->getQuantityAmount()
					. " "
					. $product->getPrice()->getQuantityUnit();
				?>
				</nobr>
			</td>
			<td>
				<ul>
					<?php
					$attributes = $product->getAttributes();
					foreach ($attributes as $attribute) {
					?>
					<li>
						<strong>
						<?php
						echo $attribute->getName();
						?>
						</strong>:
						<?php
						foreach ($attribute->getValues() as $value) {
							echo $value . ", "; 
						}
						?>
					</li>
					<?php
					}
					?>
				</ul>
			</td>
			<td>
				<nobr>
				<a href="?product=
				<?php
				echo $product->getID();
				?>
				&stocklevel=plus">+</a> 
				<?php
				echo $product->getStockLevel();
				?>
				<?php
				if ($product->getStockLevel() > 0) {
					?>
					<a href="?product=
					<?php
					echo $product->getID();
					?>
					&stocklevel=minus">-</a>
					<?php
				}
				?>
				</nobr>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
</body>
</html>