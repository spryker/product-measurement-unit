<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductMeasurementUnit\Business\CartChange\CartChangeSalesUnitExpander;
use Spryker\Zed\ProductMeasurementUnit\Business\CartChange\CartChangeSalesUnitExpanderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\CartChange\Checker\ItemProductMeasurementSalesUnitChecker;
use Spryker\Zed\ProductMeasurementUnit\Business\CartChange\Checker\ItemProductMeasurementSalesUnitCheckerInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Creator\ProductMeasurementUnitCreator;
use Spryker\Zed\ProductMeasurementUnit\Business\Creator\ProductMeasurementUnitCreatorInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Deleter\ProductMeasurementUnitDeleter;
use Spryker\Zed\ProductMeasurementUnit\Business\Deleter\ProductMeasurementUnitDeleterInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractor;
use Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractorInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Hydrator\CartReorderItemHydrator;
use Spryker\Zed\ProductMeasurementUnit\Business\Hydrator\CartReorderItemHydratorInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Installer\ProductMeasurementUnitInstaller;
use Spryker\Zed\ProductMeasurementUnit\Business\Installer\ProductMeasurementUnitInstallerInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Merger\CartReorderItemMerger;
use Spryker\Zed\ProductMeasurementUnit\Business\Merger\CartReorderItemMergerInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\CartChange\CartChangeExpander;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\CartChange\CartChangeExpanderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\Order\OrderExpander;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\Order\OrderExpanderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\Order\OrderItemExpander;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\Order\OrderItemExpanderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitGroupKeyGenerator;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitGroupKeyGeneratorInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReader;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValue;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValueInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpander;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpanderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Reader\ProductMeasurementUnitReader;
use Spryker\Zed\ProductMeasurementUnit\Business\Reader\ProductMeasurementUnitReaderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Updater\ProductMeasurementUnitUpdater;
use Spryker\Zed\ProductMeasurementUnit\Business\Updater\ProductMeasurementUnitUpdaterInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Validator\ProductMeasurementUnitValidator;
use Spryker\Zed\ProductMeasurementUnit\Business\Validator\ProductMeasurementUnitValidatorInterface;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToEventFacadeInterface;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface;
use Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitDependencyProvider;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig getConfig()
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface getEntityManager()
 */
class ProductMeasurementUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Creator\ProductMeasurementUnitCreatorInterface
     */
    public function createProductMeasurementUnitCreator(): ProductMeasurementUnitCreatorInterface
    {
        return new ProductMeasurementUnitCreator(
            $this->createProductMeasurementUnitValidator(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Updater\ProductMeasurementUnitUpdaterInterface
     */
    public function createProductMeasurementUnitUpdater(): ProductMeasurementUnitUpdaterInterface
    {
        return new ProductMeasurementUnitUpdater(
            $this->createProductMeasurementUnitValidator(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Deleter\ProductMeasurementUnitDeleterInterface
     */
    public function createProductMeasurementUnitDeleter(): ProductMeasurementUnitDeleterInterface
    {
        return new ProductMeasurementUnitDeleter(
            $this->createProductMeasurementUnitValidator(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Validator\ProductMeasurementUnitValidatorInterface
     */
    public function createProductMeasurementUnitValidator(): ProductMeasurementUnitValidatorInterface
    {
        return new ProductMeasurementUnitValidator(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValueInterface
     */
    public function createProductMeasurementSalesUnitValue(): ProductMeasurementSalesUnitValueInterface
    {
        return new ProductMeasurementSalesUnitValue();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface
     */
    public function createProductMeasurementSalesUnitReader(): ProductMeasurementSalesUnitReaderInterface
    {
        return new ProductMeasurementSalesUnitReader(
            $this->getRepository(),
            $this->getUtilMeasurementUnitConversionService(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitGroupKeyGeneratorInterface
     */
    public function createProductMeasurementSalesUnitItemGroupKeyGenerator(): ProductMeasurementSalesUnitGroupKeyGeneratorInterface
    {
        return new ProductMeasurementSalesUnitGroupKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Merger\CartReorderItemMergerInterface
     */
    public function createCartReorderItemMerger(): CartReorderItemMergerInterface
    {
        return new CartReorderItemMerger($this->createProductMeasurementUnitItemExtractor());
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Hydrator\CartReorderItemHydratorInterface
     */
    public function createCartReorderItemHydrator(): CartReorderItemHydratorInterface
    {
        return new CartReorderItemHydrator(
            $this->getRepository(),
            $this->createProductMeasurementUnitItemExtractor(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractorInterface
     */
    public function createProductMeasurementUnitItemExtractor(): ProductMeasurementUnitItemExtractorInterface
    {
        return new ProductMeasurementUnitItemExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface
     */
    public function getUtilMeasurementUnitConversionService(): ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitDependencyProvider::SERVICE_UTIL_MEASUREMENT_UNIT_CONVERSION);
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToEventFacadeInterface
     */
    public function getEventFacade(): ProductMeasurementUnitToEventFacadeInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ProductMeasurementUnitToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\CartChange\CartChangeExpanderInterface
     */
    public function createCartChangeExpander(): CartChangeExpanderInterface
    {
        return new CartChangeExpander(
            $this->createProductMeasurementSalesUnitReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\CartChange\Checker\ItemProductMeasurementSalesUnitCheckerInterface
     */
    public function createItemProductMeasurementSalesUnitChecker(): ItemProductMeasurementSalesUnitCheckerInterface
    {
        return new ItemProductMeasurementSalesUnitChecker(
            $this->getRepository(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Installer\ProductMeasurementUnitInstallerInterface
     */
    public function createProductMeasurementUnitInstaller(): ProductMeasurementUnitInstallerInterface
    {
        return new ProductMeasurementUnitInstaller(
            $this->getConfig(),
            $this->getEntityManager(),
            $this->getEventFacade(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\Order\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander(
            $this->getRepository(),
            $this->createProductMeasurementUnitTranslationExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\Order\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander(
            $this->getRepository(),
            $this->createProductMeasurementUnitTranslationExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpanderInterface
     */
    public function createProductMeasurementUnitTranslationExpander(): ProductMeasurementUnitTranslationExpanderInterface
    {
        return new ProductMeasurementUnitTranslationExpander(
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\CartChange\CartChangeSalesUnitExpanderInterface
     */
    public function createCartChangeSalesUnitExpander(): CartChangeSalesUnitExpanderInterface
    {
        return new CartChangeSalesUnitExpander(
            $this->getRepository(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Reader\ProductMeasurementUnitReaderInterface
     */
    public function createProductMeasurementUnitReader(): ProductMeasurementUnitReaderInterface
    {
        return new ProductMeasurementUnitReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductMeasurementUnitToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitDependencyProvider::FACADE_STORE);
    }
}
