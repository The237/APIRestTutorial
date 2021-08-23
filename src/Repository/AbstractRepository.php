<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
/*Au lieu de charger PagerFanta\Adapater\DoctrineORMAdapter; plutot charger la ligne ci-dessous 
afin d'éviter un bug : avant bien vouloir faire un composer req pagerfanta/doctrine-orm-adapter*/
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractRepository extends EntityRepository
{
    protected function paginate(QueryBuilder $qb,$limit = 20, $offset = 0)
    {
        if($limit == 0 || $offset ==0)
        {
            throw new \LogicException('$limit & $offset must be greater than 0.');
        }

        $pager = new Pagerfanta(new QueryAdapter($qb));
        $currentPage = ceil(($offset+1)/$limit);
        $pager->setCurrentPage($currentPage);
        $pager->setMaxPerPage((int) $limit);

        return $pager;
    }
}