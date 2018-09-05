<?php
/**
 * DISCLAIMER :
 *
 * Do not edit or add to this file if you wish to upgrade Smile ElasticSuite to newer
 * versions in the future.
 *
 * @category  Smile_Elasticsuite
 * @package   Smile\ElasticsuiteCore
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\ElasticsuiteCore\Search\Request\ContainerConfiguration\BaseConfig;

/**
 * ElasticSuite search requests XML converter.
 *
 * @category  Smile
 * @package   Smile\ElasticsuiteCore
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Converter extends \Magento\Framework\Search\Request\Config\Converter
{
    /**
     * @var string
     */
    const FILTERS_PATH = 'filters/filter';

    /**
     * @var string
     */
    const AGGREGATIONS_PATH = 'aggregations/aggregation';

    /**
     * @var string
     */
    const METRICS_PATH = 'metrics/metric';

    /**
     * Convert config.
     *
     * @param \DOMDocument $source XML file read.
     *
     * @return array
     */
    public function convert($source)
    {
        // Due to SimpleXML not deleting comment we have to strip them before using the source.
        $source = $this->stripComments($source);

        /** @var \DOMNodeList $requestNodes */
        $requestNodes = $source->getElementsByTagName('request');
        $xpath        = new \DOMXPath($source);
        $requests     = [];

        foreach ($requestNodes as $requestNode) {
            $simpleXmlNode = simplexml_import_dom($requestNode);
            /** @var \DOMElement $requestNode */
            $name               = $requestNode->getAttribute('name');
            $request            = $this->mergeAttributes((array) $simpleXmlNode);
            $request['filters']      = $this->parseFilters($xpath, $requestNode);
            $request['aggregations'] = $this->parseAggregations($xpath, $requestNode);
            $requests[$name]    = $request;
        }

        return $requests;
    }

    /**
     * This method remove all comments of an XML document.
     *
     * @param \DOMDocument $source Document to be cleansed.
     *
     * @return \DOMDocument
     */
    private function stripComments(\DOMDocument $source)
    {
        $xpath = new \DOMXPath($source);

        foreach ($xpath->query('//comment()') as $commentNode) {
            $commentNode->parentNode->removeChild($commentNode);
        }

        return $source;
    }

    /**
     * Parse filters from request node configuration.
     *
     * @param \DOMXPath $xpath           XPath access to the document parsed.
     * @param \DOMNode  $requestRootNode Request node to be parsed.
     *
     * @return array
     */
    private function parseFilters(\DOMXPath $xpath, \DOMNode $requestRootNode)
    {
        $filters = [];

        foreach ($xpath->query(self::FILTERS_PATH, $requestRootNode) as $filterNode) {
            $filters[$filterNode->getAttribute('name')] = $filterNode->nodeValue;
        }

        return $filters;
    }

    /**
     * Parse aggregations from request node configuration.
     *
     * @param \DOMXPath $xpath    XPath access to the document parsed.
     * @param \DOMNode  $rootNode Request node to be parsed.
     *
     * @return array
     */
    private function parseAggregations(\DOMXPath $xpath, \DOMNode $rootNode)
    {
        $aggs = [];

        foreach ($xpath->query(self::AGGREGATIONS_PATH, $rootNode) as $aggNode) {
            $bucketName   = $aggNode->getAttribute('name');
            $bucketConfig = [];

            foreach ($aggNode->attributes as $attribute) {
                $bucketConfig[$attribute->name] = $attribute->value;
            }
            $bucketConfig['childBuckets'] = $this->parseAggregations($xpath, $aggNode);
            $bucketConfig['metrics'] = $this->parseMetrics($xpath, $aggNode);
            $aggs[$bucketName] = $bucketConfig;
        }

        return $aggs;
    }

    /**
     * Parse metrics from a bucket.
     *
     * @param \DOMXPath $xpath    XPath access to the document parsed.
     * @param \DOMNode  $rootNode Aggregation node to be parsed.
     *
     * @return array
     */
    private function parseMetrics(\DOMXPath $xpath, \DOMNode $rootNode)
    {
        $metrics = [];

        foreach ($xpath->query(self::METRICS_PATH, $rootNode) as $metricNode) {
            $metric = [];
            foreach ($metricNode->attributes as $attribute) {
                $metric[$attribute->name] = $attribute->value;
            }
            $metrics[$metric['name']] = $metric;
        }

        return $metrics;
    }
}
