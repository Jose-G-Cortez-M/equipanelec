<?php

namespace App\Service\Movimiento;


use App\Form\Model\MovimientoDto;
use App\Form\Type\MovimientoFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MovimientoFormProcessor
{

    private CreateMovimiento $createMovimiento;
    private FormFactoryInterface $formFactory;

    public function __construct(
        CreateMovimiento $createMovimiento,
        FormFactoryInterface $formFactory
    ) {
        $this->createMovimiento = $createMovimiento;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request): array
    {
        $movimientoDto = new MovimientoDto();
        $form = $this->formFactory->create(MovimientoFormType::class, $movimientoDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'El formulario no se pudo enviar'];
        }
        $movimiento = ($this->createMovimiento)($movimientoDto->getNombre());
        return [null, $movimiento];
    }
}