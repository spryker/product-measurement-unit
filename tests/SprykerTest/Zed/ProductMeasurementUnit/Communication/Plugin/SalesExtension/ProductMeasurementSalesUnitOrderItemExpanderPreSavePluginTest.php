<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Communication\Plugin\SalesExtension;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductMeasurementSalesUnitBuilder;
use Generated\Shared\DataBuilder\ProductMeasurementUnitBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\SalesExtension\ProductMeasurementSalesUnitOrderItemExpanderPreSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Communication
 * @group Plugin
 * @group SalesExtension
 * @group ProductMeasurementSalesUnitOrderItemExpanderPreSavePluginTest
 * Add your own group annotations below this line
 */
class ProductMeasurementSalesUnitOrderItemExpanderPreSavePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\SalesExtension\ProductMeasurementSalesUnitOrderItemExpanderPreSavePlugin
     */
    protected $productMeasurementSalesUnitOrderItemExpanderPreSavePlugin;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productMeasurementSalesUnitOrderItemExpanderPreSavePlugin = new ProductMeasurementSalesUnitOrderItemExpanderPreSavePlugin();
    }

    /**
     * @return void
     */
    public function testExpandOrderItemAddsMeasurementUnitInfo(): void
    {
        //Assign
        $productMeasurementUnit = (new ProductMeasurementUnitBuilder())->build();
        $quantitySalesUnitTransfer = (new ProductMeasurementSalesUnitBuilder(['productMeasurementUnit' => $productMeasurementUnit]))->build();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantitySalesUnit($quantitySalesUnitTransfer);

        //Act
        $salesOrderItemEntity = $this->productMeasurementSalesUnitOrderItemExpanderPreSavePlugin->expandOrderItem(
            new QuoteTransfer(),
            $itemTransfer,
            new SpySalesOrderItemEntityTransfer()
        );

        //Assert
        $this->assertSame($productMeasurementUnit->getName(), $salesOrderItemEntity->getQuantityMeasurementUnitName());
        $this->assertSame($quantitySalesUnitTransfer->getPrecision(), $salesOrderItemEntity->getQuantityMeasurementUnitPrecision());
        $this->assertSame($quantitySalesUnitTransfer->getConversion(), $salesOrderItemEntity->getQuantityMeasurementUnitConversion());
    }
}