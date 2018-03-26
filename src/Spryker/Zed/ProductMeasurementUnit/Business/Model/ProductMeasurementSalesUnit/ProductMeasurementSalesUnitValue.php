<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\ItemTransfer;

class ProductMeasurementSalesUnitValue implements ProductMeasurementSalesUnitValueInterface
{
    const FLOAT_PRECISION = 0.000001;

    /**
     * @see ProductMeasurementSalesUnitValue::calculateNormalizedValue()
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    public function calculateQuantityNormalizedSalesUnitValue(ItemTransfer $itemTransfer)
    {
        $itemTransfer
            ->requireQuantitySalesUnit()
            ->requireQuantity()
                ->getQuantitySalesUnit()
                ->requireConversion()
                ->requirePrecision();

        return $this->calculateNormalizedValue(
            $itemTransfer->getQuantity(),
            $itemTransfer->getQuantitySalesUnit()->getConversion(),
            $itemTransfer->getQuantitySalesUnit()->getPrecision()
        );
    }

    /**
     * Checks if the given availability value (example: quantity) can be converted to a sales unit value with
     * a given precision without information loss.
     *
     * @see ProductMeasurementSalesUnitValue::calculateNormalizedValue()
     *
     * @param int $availabilityValue
     * @param float $unitToAvailabilityConversion
     * @param int $unitPrecision
     *
     * @return bool
     */
    public function isIntegerSalesUnitValue($availabilityValue, $unitToAvailabilityConversion, $unitPrecision)
    {
        $salesUnitValue = $this->calculateFloatNormalizedValue($availabilityValue, $unitToAvailabilityConversion, $unitPrecision);
        if (abs($salesUnitValue - round($salesUnitValue)) < static::FLOAT_PRECISION) {
            return true;
        }

        return false;
    }

    /**
     * Converts a value (representing availability) to a given unit with a given precision.
     *
     * @example
     * 8 quantity is ordered (availability value),
     * to be displayed sales unit is KG with a unit precision of 100 (exchanged value can be displayed up to 2 decimals),
     * and 2 KG represents 1 quantity (sales unit to stock conversion ratio is 0.5).
     * The retrieved normalized sales unit value is 1600 (16.00 KG when displayed).
     *
     * @param int $availabilityValue
     * @param float $unitToAvailabilityConversion
     * @param int $unitPrecision
     *
     * @return int
     */
    protected function calculateNormalizedValue($availabilityValue, $unitToAvailabilityConversion, $unitPrecision)
    {
        return (int)round(
            $this->calculateFloatNormalizedValue($availabilityValue, $unitToAvailabilityConversion, $unitPrecision)
        );
    }

    /**
     * @see ProductMeasurementSalesUnitValue::calculateNormalizedValue()
     *
     * @param int $availabilityValue
     * @param float $unitToAvailabilityConversion
     * @param int $unitPrecision
     *
     * @return float
     */
    protected function calculateFloatNormalizedValue($availabilityValue, $unitToAvailabilityConversion, $unitPrecision)
    {
        return $availabilityValue / $unitToAvailabilityConversion * $unitPrecision;
    }
}