<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductMeasurementUnitToGlossaryFacadeInterface
{
    /**
     * @param string $keyName
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], ?LocaleTransfer $localeTransfer = null);
}
