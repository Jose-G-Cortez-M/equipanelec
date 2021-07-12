<?php

namespace App\Controller\Api;

use App\Form\Model\MovimientoDto;
use App\Service\MovimientoManager;
use Symfony\Component\HttpFoundation\Request;
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
       /**
     * @Rest\Post(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        MovimientoManager $movimientoManager
    ) {
        $movimientoDto = new MovimientoDto();
        $form = $this->createForm(MovimientoFormType::class, $movimientoDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $movimiento = $movimientoManager->create();
            $movimiento->setNombre($movimientoDto->nombre);
            $movimientoManager->save($movimiento);
            return $movimiento;
        }
        return $form;
    }
    
}
