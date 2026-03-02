<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Updater;

use Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductMeasurementUnit\Business\Validator\ProductMeasurementUnitValidatorInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface;

class ProductMeasurementUnitUpdater implements ProductMeasurementUnitUpdaterInterface
{
    use TransactionTrait;

    public function __construct(
        protected ProductMeasurementUnitValidatorInterface $productMeasurementUnitValidator,
        protected ProductMeasurementUnitEntityManagerInterface $productMeasurementUnitEntityManager
    ) {
    }

    public function update(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productMeasurementUnitCollectionRequestTransfer) {
            return $this->executeUpdateTransaction($productMeasurementUnitCollectionRequestTransfer);
        });
    }

    protected function executeUpdateTransaction(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        $productMeasurementUnitCollectionResponseTransfer = new ProductMeasurementUnitCollectionResponseTransfer();

        $invalidCodes = [];
        $this->productMeasurementUnitValidator->validateProductMeasurementUnitsExist($productMeasurementUnitCollectionRequestTransfer, $productMeasurementUnitCollectionResponseTransfer, $invalidCodes);
        $this->productMeasurementUnitValidator->validatePrecision($productMeasurementUnitCollectionRequestTransfer, $productMeasurementUnitCollectionResponseTransfer, $invalidCodes);

        if (count($productMeasurementUnitCollectionResponseTransfer->getErrors()) > 0 && $productMeasurementUnitCollectionRequestTransfer->getIsTransactional() !== false) {
            return $productMeasurementUnitCollectionResponseTransfer;
        }

        foreach ($productMeasurementUnitCollectionRequestTransfer->getProductMeasurementUnits() as $productMeasurementUnitTransfer) {
            if (in_array($productMeasurementUnitTransfer->getCode(), $invalidCodes, true)) {
                continue;
            }

            $savedUnitTransfer = $this->productMeasurementUnitEntityManager->saveProductMeasurementUnit($productMeasurementUnitTransfer);
            $productMeasurementUnitCollectionResponseTransfer->addProductMeasurementUnit($savedUnitTransfer);
        }

        return $productMeasurementUnitCollectionResponseTransfer;
    }
}
