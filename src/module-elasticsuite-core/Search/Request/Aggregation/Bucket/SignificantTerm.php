<?php
/**
 * DISCLAIMER
*
 * Do not edit or add to this file if you wish to upgrade Smile ElasticSuite to newer
 * versions in the future.
*
 * @category  Smile
 * @package   Smile\ElasticsuiteCore
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
*/

namespace Smile\ElasticsuiteCore\Search\Request\Aggregation\Bucket;

use Smile\ElasticsuiteCore\Search\Request\BucketInterface;
use Smile\ElasticsuiteCore\Search\Request\QueryInterface;

/**
 * Significant term bucket implementation.
 *
 * @category Smile
 * @package  Smile\ElasticsuiteCore
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class SignificantTerm extends AbstractBucket
{
    const ALGORITHM_GND = 'gnd';

    const ALGORITHM_CHI_SQUARE = 'chi_sqare';

    const ALGORITHM_JLH = 'jlh';

    const ALGORITHM_PERCENTAGE = 'percentage';

    /**
     * @var integer
     */
    private $size;

    /**
     * @var integer
     */
    private $minDocCount;

    /**
     * @var string
     */
    private $algorithm;

    /**
     * Constructor.
     *
     * @param string            $name         Bucket name.
     * @param string            $field        Bucket field.
     * @param Metric[]          $metrics      Bucket metrics.
     * @param BucketInterface[] $childBuckets Child buckets.
     * @param string            $nestedPath   Nested path for nested bucket.
     * @param QueryInterface    $filter       Bucket filter.
     * @param QueryInterface    $nestedFilter Nested filter for the bucket.
     * @param integer           $size         Bucket size.
     * @param string            $algotithm    Algorithm used
     */
    public function __construct(
        $name,
        $field,
        array $metrics = [],
        array $childBuckets = [],
        $nestedPath = null,
        QueryInterface $filter = null,
        QueryInterface $nestedFilter = null,
        $size = 0,
        $minDocCount = 5,
        $algotithm = self::ALGORITHM_GND
    ) {
        parent::__construct($name, $field, $metrics, $childBuckets, $nestedPath, $filter, $nestedFilter);

        $this->minDocCount = $minDocCount;
        $this->algorithm   = $algotithm;
        $this->size        = $size > 0 && $size < self::MAX_BUCKET_SIZE ? $size : self::MAX_BUCKET_SIZE;
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return BucketInterface::TYPE_SIGNIFICANT_TERM;
    }

    /**
     * Bucket size.
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    public function getMinDocCount()
    {
        return $this->minDocCount;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }
}
