<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Component\Transformer;

use Sonata\Component\Product\Pool;

abstract class BaseTransformer
{
    /**
     * @var instance logger
     */
    protected $logger;

    /**
     * @var the product pool
     */
    protected $productPool;

    /**
     * @var the transformer option
     */
    protected $options;

    public function __construct(Pool $productPool, $logger = null)
    {
        $this->productPool = $productPool;
        $this->logger = $logger;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function setProductPool($pool)
    {
        $this->productPool = $pool;
    }

    public function getProductPool()
    {
        return $this->productPool;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }
}