<?php

namespace App\Controller\Api;

use FOS\RestBundle\View\View;
use App\Repository\MovimientoRepository;
use App\Service\Movimiento\MovimientoFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class MovimientoController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/movimientos")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        MovimientoRepository $movimientoRepository
    ) {
        return $movimientoRepository->findAll();
    }
       /**
     * @Rest\Post(path="/movimientos")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        MovimientoFormProcessor $movimientoFormProcessor
    ) {
        [$movimiento, $error] = ($movimientoFormProcessor)($request);
        $statusCode = $movimiento ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $movimiento ?? $error;
        return View::create($data, $statusCode);
    }
    
}
