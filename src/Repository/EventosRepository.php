<?php

namespace App\Repository;

use App\Entity\Eventos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Exception;

/**
 * @extends ServiceEntityRepository<Eventos>
 */
class EventosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eventos::class);
    }



    /**
     * Obtener Todos los sismos registrados en la tabla historico_sismos que tengan informe
     */
    public function findAllEventos(): ?array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql ="select i.*, e.nombre  
                from informes.historico_sismos AS i
                INNER JOIN lis.estaciones AS e
                ON  e.estacion = i.lugarAceleracion
                Where i.informe = 1
                Order by i.fechaEvento DESC;";
        try {

            $datos= $conn->executeQuery($sql);
            return $datos->fetchAllAssociative();
        }catch (Exception $e){
            return [];
        }


    }


    //    /**
    //     * @return Eventos[] Returns an array of Eventos objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Eventos
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
