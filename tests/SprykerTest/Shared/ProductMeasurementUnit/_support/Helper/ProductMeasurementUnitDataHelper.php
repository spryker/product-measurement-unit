<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductMeasurementUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\SpyProductMeasurementSalesUnitEntityBuilder;
use Generated\Shared\DataBuilder\SpyProductMeasurementUnitEntityBuilder;
use Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitStore;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductMeasurementUnitDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer
     */
    public function haveProductMeasurementUnit(array $override = []): SpyProductMeasurementUnitEntityTransfer
    {
        $productMeasurementUnitEntity = (new SpyProductMeasurementUnitEntityBuilder())->build();
        $productMeasurementUnitEntity->fromArray($override, true);

        $productMeasurementUnitEntity = $this->storeProductMeasurementUnit($productMeasurementUnitEntity);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productMeasurementUnitEntity): void {
            $this->cleanupProductMeasurementUnit($productMeasurementUnitEntity->getIdProductMeasurementUnit());
        });

        return $productMeasurementUnitEntity;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function haveProductMeasurementBaseUnit(int $idProductAbstract, int $idProductMeasurementUnit): SpyProductMeasurementBaseUnitEntityTransfer
    {
        $baseUnitEntity = (new SpyProductMeasurementBaseUnitEntityTransfer())
            ->setFkProductAbstract($idProductAbstract)
            ->setFkProductMeasurementUnit($idProductMeasurementUnit);

        $baseUnitEntity = $this->storeProductMeasurementBaseUnit($baseUnitEntity);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($baseUnitEntity): void {
            $this->cleanupProductMeasurementBaseUnit($baseUnitEntity->getIdProductMeasurementBaseUnit());
        });

        return $baseUnitEntity;
    }

    /**
     * @param int $idProduct
     * @param int $idProductMeasurementUnit
     * @param int $idProductMeasurementBaseUnit
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveProductMeasurementSalesUnit(int $idProduct, int $idProductMeasurementUnit, int $idProductMeasurementBaseUnit, array $override = [])
    {
        $salesUnitEntity = (new SpyProductMeasurementSalesUnitEntityBuilder())->build();
        $salesUnitEntity
            ->setFkProductMeasurementUnit($idProductMeasurementUnit)
            ->setFkProduct($idProduct)
            ->setFkProductMeasurementBaseUnit($idProductMeasurementBaseUnit)
            ->fromArray($override, true);

        $salesUnitEntity = $this->storeProductMeasurementSalesUnit($salesUnitEntity);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($salesUnitEntity): void {
            $this->cleanupProductMeasurementSalesUnit($salesUnitEntity->getIdProductMeasurementSalesUnit());
        });

        return $salesUnitEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer $salesUnitEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    protected function storeProductMeasurementSalesUnit(
        SpyProductMeasurementSalesUnitEntityTransfer $salesUnitEntityTransfer
    ): SpyProductMeasurementSalesUnitEntityTransfer {
        $spySalesUnitEntity = new SpyProductMeasurementSalesUnit();
        $spySalesUnitEntity->fromArray($salesUnitEntityTransfer->modifiedToArray());
        $spySalesUnitEntity->save();

        $this->debug(sprintf('Inserted sales unit for product: %d', $salesUnitEntityTransfer->getFkProduct()));

        $salesUnitEntityTransfer->fromArray($spySalesUnitEntity->toArray(), true);
        $this->storeProductMeasurementSalesUnitStore($salesUnitEntityTransfer);

        return $salesUnitEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer $salesUnitEntityTransfer
     *
     * @return void
     */
    protected function storeProductMeasurementSalesUnitStore(SpyProductMeasurementSalesUnitEntityTransfer $salesUnitEntityTransfer): void
    {
        $productMeasurementSalesUnitStore = new SpyProductMeasurementSalesUnitStore();
        $productMeasurementSalesUnitStore->setFkProductMeasurementSalesUnit($salesUnitEntityTransfer->getIdProductMeasurementSalesUnit());
        $productMeasurementSalesUnitStore->setFkStore(1);
        $productMeasurementSalesUnitStore->save();

        $this->debug(sprintf('Inserted sales unit store for product: %d', $salesUnitEntityTransfer->getFkProduct()));
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer $baseUnitEntity
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    protected function storeProductMeasurementBaseUnit(SpyProductMeasurementBaseUnitEntityTransfer $baseUnitEntity): SpyProductMeasurementBaseUnitEntityTransfer
    {
        $spyBaseUnitEntity = $this->getProductMeasurementBaseUnitQuery()
            ->filterByFkProductAbstract($baseUnitEntity->getFkProductAbstract())
            ->findOneOrCreate();

        $spyBaseUnitEntity->fromArray($baseUnitEntity->modifiedToArray());
        $spyBaseUnitEntity->save();

        $this->debug(sprintf('Inserted base unit for product abstract: %d', $baseUnitEntity->getFkProductAbstract()));

        $baseUnitEntity->fromArray($spyBaseUnitEntity->toArray(), true);

        return $baseUnitEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer $productMeasurementUnitEntity
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer
     */
    protected function storeProductMeasurementUnit(
        SpyProductMeasurementUnitEntityTransfer $productMeasurementUnitEntity
    ): SpyProductMeasurementUnitEntityTransfer {
        $spyProductMeasurementUnitEntity = $this->getProductMeasurementUnitQuery()
            ->filterByCode($productMeasurementUnitEntity->getCode())
            ->findOneOrCreate();

        $spyProductMeasurementUnitEntity->fromArray($productMeasurementUnitEntity->modifiedToArray());
        $spyProductMeasurementUnitEntity->save();

        $this->debug(sprintf('Inserted product measurement unit with code: %s', $productMeasurementUnitEntity->getCode()));

        $productMeasurementUnitEntity->fromArray($spyProductMeasurementUnitEntity->toArray(), true);

        return $productMeasurementUnitEntity;
    }

    /**
     * @param int $idProductMeasurementBaseUnit
     *
     * @return void
     */
    protected function cleanupProductMeasurementBaseUnit(int $idProductMeasurementBaseUnit): void
    {
        $this->debug(sprintf('Deleting product measurement base unit: %d', $idProductMeasurementBaseUnit));

        $this->getProductMeasurementBaseUnitQuery()
            ->findByIdProductMeasurementBaseUnit($idProductMeasurementBaseUnit)
            ->delete();
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return void
     */
    protected function cleanupProductMeasurementSalesUnit(int $idProductMeasurementSalesUnit): void
    {
        $this->debug(sprintf('Deleting product measurement sales unit: %d', $idProductMeasurementSalesUnit));

        $this->getProductMeasurementSalesUnitQuery()
            ->findByIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
            ->delete();
    }

    /**
     * @param int $idProductMeasurementUnit
     *
     * @return void
     */
    protected function cleanupProductMeasurementUnit(int $idProductMeasurementUnit): void
    {
        $this->debug(sprintf('Deleting product measurement unit: %d', $idProductMeasurementUnit));

        $this->getProductMeasurementUnitQuery()
            ->findByIdProductMeasurementUnit($idProductMeasurementUnit)
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery
     */
    protected function getProductMeasurementBaseUnitQuery(): SpyProductMeasurementBaseUnitQuery
    {
        return SpyProductMeasurementBaseUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery
     */
    protected function getProductMeasurementSalesUnitQuery(): SpyProductMeasurementSalesUnitQuery
    {
        return SpyProductMeasurementSalesUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery
     */
    protected function getProductMeasurementUnitQuery(): SpyProductMeasurementUnitQuery
    {
        return SpyProductMeasurementUnitQuery::create();
    }
}
