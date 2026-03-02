<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnit;
use Propel\Runtime\Collection\Collection;

class ProductMeasurementUnitMapper implements ProductMeasurementUnitMapperInterface
{
    public function mapProductMeasurementSalesUnitTransfer(
        SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity,
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        $productMeasurementSalesUnitTransfer->fromArray($productMeasurementSalesUnitEntity->toArray(), true);

        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit(
            (new ProductMeasurementUnitTransfer())->fromArray($productMeasurementSalesUnitEntity->getProductMeasurementUnit()->toArray(), true),
        );

        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit(
            $this->mapProductMeasurementBaseUnitTransfer(
                $productMeasurementSalesUnitEntity->getProductMeasurementBaseUnit(),
                new ProductMeasurementBaseUnitTransfer(),
            ),
        );

        $productMeasurementSalesUnitTransfer->setStoreRelation(
            $this->mapProductMeasurementSalesUnitStoreRelationTransfer(
                $productMeasurementSalesUnitEntity,
                new StoreRelationTransfer(),
            ),
        );

        return $productMeasurementSalesUnitTransfer;
    }

    public function mapProductMeasurementBaseUnitTransfer(
        SpyProductMeasurementBaseUnit $productMeasurementBaseUnitEntity,
        ProductMeasurementBaseUnitTransfer $productMeasurementBaseUnitTransfer
    ): ProductMeasurementBaseUnitTransfer {
        $productMeasurementBaseUnitTransfer->fromArray($productMeasurementBaseUnitEntity->toArray(), true);

        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit(
            (new ProductMeasurementUnitTransfer())->fromArray($productMeasurementBaseUnitEntity->getProductMeasurementUnit()->toArray(), true),
        );

        return $productMeasurementBaseUnitTransfer;
    }

    public function mapProductMeasurementUnitTransfer(
        SpyProductMeasurementUnit $productMeasurementUnitEntity,
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
    ): ProductMeasurementUnitTransfer {
        $productMeasurementUnitTransfer->fromArray($productMeasurementUnitEntity->toArray(), true);

        return $productMeasurementUnitTransfer;
    }

    public function mapProductMeasurementUnitCollectionTransfer(
        Collection $productMeasurementUnitEntities,
        ProductMeasurementUnitCollectionTransfer $productMeasurementUnitCollectionTransfer
    ): ProductMeasurementUnitCollectionTransfer {
        foreach ($productMeasurementUnitEntities as $productMeasurementUnitEntity) {
            $productMeasurementUnitCollectionTransfer->addProductMeasurementUnit(
                $this->mapProductMeasurementUnitTransfer(
                    $productMeasurementUnitEntity,
                    new ProductMeasurementUnitTransfer(),
                ),
            );
        }

        return $productMeasurementUnitCollectionTransfer;
    }

    protected function mapProductMeasurementSalesUnitStoreRelationTransfer(
        SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        $storeRelationTransfer->setIdEntity($productMeasurementSalesUnitEntity->getIdProductMeasurementSalesUnit());

        $idStores = [];
        $storeTransfers = [];
        foreach ($productMeasurementSalesUnitEntity->getSpyProductMeasurementSalesUnitStores() as $spyProductMeasurementSalesUnitStore) {
            $storeTransfer = (new StoreTransfer())->fromArray(
                $spyProductMeasurementSalesUnitStore->getSpyStore()->toArray(),
                true,
            );

            $idStores[] = $storeTransfer->getIdStore();
            $storeTransfers[] = $storeTransfer;
        }

        $storeRelationTransfer
            ->setIdStores($idStores)
            ->setStores(new ArrayObject($storeTransfers));

        return $storeRelationTransfer;
    }
}
