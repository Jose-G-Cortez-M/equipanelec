<?php

namespace App\Controller\Api;

use App\Service\MovimientoManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class MovimientoController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/movimientos")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        MovimientoManager $movimientoManager
    ) {
        return $movimientoManager->getRepository()->findAll();
    }
}
