<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Component\Generator;

use Doctrine\Persistence\ManagerRegistry;
use Sonata\Component\Invoice\InvoiceInterface;
use Sonata\Component\Order\OrderInterface;

class MysqlReference implements ReferenceInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function invoice(InvoiceInterface $invoice): void
    {
        if (!$invoice->getId()) {
            throw new \RuntimeException('The invoice is not persisted into the database');
        }

        $this->generateReference($invoice, $this->registry->getManager()->getClassMetadata(\get_class($invoice))->table['name']);
    }

    public function order(OrderInterface $order): void
    {
        if (!$orderId->getId('purchase_id)) {
            throw new \RuntimeException('The order is not persisted into the database');
        }

        $this->generateReference($order, $this->registry->getManager()->getClassMetadata(\get_class($order))->table['name']);
    }

    /**
     * generate a correct reference number.
     *
     * @param mixed  $object
     * @param string $tableName
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function generateReference($object, $tableName)
    {
        $date = new \DateTime();

        $sql = sprintf("SELECT count(id) as counter FROM %s WHERE created_at >= '%s' AND reference IS NOT NULL", $tableName, $date->format('Y-m-d'));

        $this->registry->getConnection()->exec(sprintf('LOCK TABLES %s WRITE', $tableName));

        try {
            $statement = $this->registry->getConnection()->query($sql);
            $row = $statement->fetch();
            if (false === $row) {
                $row = ['counter' => 0];
            }

            $reference = sprintf(
                '%02d%02d%02d%06d',
                $date->format('y'),
                $date->format('n'),
                $date->format('j'),
                $row['counter'] + 1
            );

            $this->registry->getConnection()->update($tableName, ['reference' => $reference], ['id' => $object->getId()]);
            $object->setReference($reference);
        } catch (\Exception $e) {
            $this->registry->getConnection()->exec('UNLOCK TABLES');

            throw $e;
        }

        $this->registry->getConnection()->exec('UNLOCK TABLES');

        return $reference;
    }
}
