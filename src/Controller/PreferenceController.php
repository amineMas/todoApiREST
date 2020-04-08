<?php

namespace App\Controller;

use App\Entity\TaskList;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class PreferenceController extends AbstractFOSRestController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getPreferencesAction(TaskList $list)
    {
        return $this->view($list->getPreferences(), 200);
    }

    /**
     * @Rest\RequestParam(name="sortValue", description="The value will be used to sort the list", nullable=false)
     */
    public function sortPreferencesAction(ParamFetcher $paramFetcher, TaskList $list)
    {
        $sortValue = $paramFetcher->get('sortValue');
        if($sortValue) {
            $list->getPreferences()->setSortValue($sortValue);
            $this->entityManager->persist($list);
            $this->entityManager->flush();
            return $this->view(null, 204);
        }

        $data['code'] = 409;
        $data['message'] = 'The sortValue cannot be null';

        return $this->view($data, 409);
    }

    /**
     * @Rest\RequestParam(name="filterValue", description="The filter value", nullable=false)
     */
    public function filterPreferencesAction(ParamFetcher $paramFetcher, TaskList $list)
    {
        $filterValue = $paramFetcher->get('filterValue');
        if ($filterValue) {
            $list->getPreferences()->setFilterValue($filterValue);
            $this->entityManager->persist($list);
            $this->entityManager->flush();
            return $this->view(null, 204);
        }

        $data['code'] = 409;
        $data['message'] = 'The filterValue cannot be null';

        return $this->view($data, 409);
    }
}
