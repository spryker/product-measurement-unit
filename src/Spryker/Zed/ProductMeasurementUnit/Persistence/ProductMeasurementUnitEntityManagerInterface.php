<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

interface ProductMeasurementUnitEntityManagerInterface
{
    /**
     * @param int $idProductMeasurementUnit
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function deleteProductMeasurementUnit(int $idProductMeasurementUnit): void;

    public function saveProductMeasurementUnit(
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
    ): ProductMeasurementUnitTransfer;
}
