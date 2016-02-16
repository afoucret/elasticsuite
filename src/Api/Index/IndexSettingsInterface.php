<?php
/**
 * DISCLAIMER :
 *
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile_ElasticSuite
 * @package   Smile\ElasticSuiteCore
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\ElasticSuiteCore\Api\Index;

/**
 * Provides acces to indices related settings / configuration.
 *
 * @category Smile_ElasticSuite
 * @package  Smile\ElasticSuiteCore
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
interface IndexSettingsInterface
{
    /**
     * Returns the index alias for an identifier (eg. catalog_product) by store.
     *
     * @param string                                                $indexIdentifier Index identifier.
     * @param integer|string|\Magento\Store\Api\Data\StoreInterface $store           Store.
     *
     * @return string
     */
    public function getIndexAliasFromIdentifier($indexIdentifier, $store);

    /**
     * Create a new index for an identifier (eg. catalog_product) by store including current date.
     *
     * @param string                                                $indexIdentifier Index identifier.
     * @param integer|string|\Magento\Store\Api\Data\StoreInterface $store           Store.
     *
     * @return string
     */
    public function createIndexNameFromIdentifier($indexIdentifier, $store);

    /**
     * Load analysis settings by store.
     *
     * @param integer|string|\Magento\Store\Api\Data\StoreInterface $store Store.
     *
     * @return array
     */
    public function getAnalysisSettings($store);

    /**
     * Returns settings used during index creation.
     *
     * @return array
     */
    public function getCreateIndexSettings();

    /**
     * Returns settings used when installing an index.
     *
     * @return array
     */
    public function getInstallIndexSettings();

    /**
     * Returns the list of the available indices declared in elasticsearch/indices.xml.
     *
     * @return array
     */
    public function getIndicesConfig();

    /**
     * Get indexing batch size configured.
     *
     * @return integer
     */
    public function getBatchIndexingSize();
}
