<?php

namespace App\Service;

use App\Entity\Material;
use App\Form\Model\MaterialDto;
use App\Form\Model\MovimientoDto;
use App\Form\Type\MaterialFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Ramsey\Uuid\Uuid;

Class MaterialFormProcessor
{
    private $materialManager;
    private $fileUploader;
    private $formFactory;

    public function __construct(
        MaterialManager $materialManager,
        MovimientoManager $movimientoManager,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory
    ) {
        $this->materialManager = $materialManager;
        $this->movimientoManager = $movimientoManager;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Material $material, Request $request)
    {
        $materialDto = MaterialDto::createFromMaterial($material);
        $originalMovimientos = new ArrayCollection();
        foreach ($material->getMovimientos() as $movimiento) {
            $movimientoDto = MovimientoDto::createFromMovimiento($movimiento);
            $materialDto->movimientos[] = $movimientoDto;
            $originalMovimientos->add($movimientoDto);
        }
        $form = $this->formFactory->create(MaterialFormType::class, $materialDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if ($form->isValid()) {
            // Remove movimientos
            foreach ($originalMovimientos as $originalMovimientoDto) {
                if (!\in_array($originalMovimientoDto, $materialDto->movimientos)) {
                    $movimiento = $this->MovimeintoManager->find(Uuid::fromString($originalMovimientoDto->id));
                    $material->removeMovimiento($movimiento);
                }
            }

            // Add Movimientos
            foreach ($materialDto->movimientos as $newMovimientoDto) {
                if (!$originalMovimientos->contains($newMovimientoDto)) {
                    $movimiento = null;
                    if ($newMovimientoDto->id !== null) {
                        $movimiento = $this->movimientoManager->create();
                        $movimiento->setNombre($newMovimientoDto->nombre);
                        $this->movimientoManager->persist($movimiento);
                    }
                    $material->addMovimiento($movimiento);
                }
            }
            $material->setNombre($materialDto->nombre);
            if ($materialDto->base64Imagen) {
                $filename = $this->fileUploader->uploadBase64File($materialDto->base64Imagen);
                $material->setImagen($filename);
            }
            $this->materialManager->save($material);
            $this->materialManager->reload($material);
            return [$material, null];
        }
        return [null, $form];
    }


}