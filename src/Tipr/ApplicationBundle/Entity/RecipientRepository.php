<?php

namespace Tipr\ApplicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RecipientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RecipientRepository extends EntityRepository
{
    public function getRecentDonationsLimit($recipient, $limit)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('c')
            ->from('Tipr\ApplicationBundle\Entity\Donation', 'c')
            ->where('c.recipient = :recipient')
            ->setParameter('recipient', $recipient)
            ->orderBy('c.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getHighestDontionsLimit($recipient, $limit)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('c')
            ->from('Tipr\ApplicationBundle\Entity\Donation', 'c')
            ->where('c.recipient = :recipient')
            ->setParameter('recipient', $recipient)
            ->orderBy('c.amount')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getDonationsThisWeek($recipient)
    {
        $start = strtotime("-1 month");
        $end = strtotime("tomorrow");

        $qb = $this->_em->createQueryBuilder();
        $donations = $qb->select('c')
            ->from('Tipr\ApplicationBundle\Entity\Donation', 'c')
            ->where('(c.date BETWEEN :start AND :end) AND c.recipient = :recipient')
            ->setParameter('recipient', $recipient)
            ->setParameter('start', date('Y-m-d', $start))
            ->setParameter('end', date('Y-m-d', $end))
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();

        $totals = array();
        foreach ($donations as $donation) {
            if (isset($totals[date_format($donation->getDate(), 'Y-m-d')])) {
                $totals[date_format($donation->getDate(), 'Y-m-d')] += $donation->getAmount();
            } else {
                $totals[date_format($donation->getDate(), 'Y-m-d')] = $donation->getAmount();
            }
        }
        return $totals;
    }

    public function getTotalThisDay($recipient)
    {
        $start = strtotime("-1 day");
        $end = strtotime("tomorrow");

        $qb = $this->_em->createQueryBuilder();
        $donations = $qb->select('c')
            ->from('Tipr\ApplicationBundle\Entity\Donation', 'c')
            ->where('(c.date BETWEEN :start AND :end) AND c.recipient = :recipient')
            ->setParameter('recipient', $recipient)
            ->setParameter('start', date('Y-m-d', $start))
            ->setParameter('end', date('Y-m-d', $end))
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();

        $total = 0;
        foreach ($donations as $donation) {
            $total += $donation->getAmount();
        }

        return $total;
    }

    public function getTotalThisWeek($recipient)
    {
        $start = strtotime("-1 week");
        $end = strtotime("tomorrow");

        $qb = $this->_em->createQueryBuilder();
        $donations = $qb->select('c')
            ->from('Tipr\ApplicationBundle\Entity\Donation', 'c')
            ->where('(c.date BETWEEN :start AND :end) AND c.recipient = :recipient')
            ->setParameter('recipient', $recipient)
            ->setParameter('start', date('Y-m-d', $start))
            ->setParameter('end', date('Y-m-d', $end))
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();

        $total = 0;
        foreach ($donations as $donation) {
            $total += $donation->getAmount();
        }

        return $total;
    }

    public function getTotalThisMonth($recipient)
    {
        $start = strtotime("-1 month");
        $end = strtotime("tomorrow");

        $qb = $this->_em->createQueryBuilder();
        $donations = $qb->select('c')
            ->from('Tipr\ApplicationBundle\Entity\Donation', 'c')
            ->where('(c.date BETWEEN :start AND :end) AND c.recipient = :recipient')
            ->setParameter('recipient', $recipient)
            ->setParameter('start', date('Y-m-d', $start))
            ->setParameter('end', date('Y-m-d', $end))
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();

        $total = 0;
        foreach ($donations as $donation) {
            $total += $donation->getAmount();
        }

        return $total;
    }
}
