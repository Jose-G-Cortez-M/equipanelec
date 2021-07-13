<?php

namespace App\Service;

use App\Entity\Material;
use App\Form\Model\MaterialDto;
use App\Form\Model\MovimientoDto;
use App\Form\Type\MaterialFormType;
use App\Repository\MaterialRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;

Class MaterialFormProcessor
{
    private MaterialRepository $materialRepository;
    private MovimientoManager $movimientoManager;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;

    public function __construct(
        MaterialRepository $materialRepository,
        MovimientoManager $movimientoManager,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory
    ) {
        $this->materialRepository = $materialRepository;
        $this->movimientoManager = $movimientoManager;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Material $material, Request $request)
    {
        $materialDto = MaterialDto::createFromMaterial($material);
        /** @var MovimientoDto[]|ArrayCollection */
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
            // Remove Movimientos
            foreach ($originalMovimientos as $originalMovimientoDto) {
                if (!\in_array($originalMovimientoDto, $materialDto->movimientos)) {
                    $movimiento = $this->movimientoManager->find($originalMovimientoDto->getId());
                    $material->removeMovimiento($movimiento);
                }
            }

            // Add Movimientos
            foreach ($materialDto->getMovimientos() as $newMovimientoDto) {
                if (!$originalMovimientos->contains($newMovimientoDto)) {
                    $movimiento = null;
                    if ($newMovimientoDto->getId() !== null) {
                        $movimiento = $this->movimientoManager->find($newMovimientoDto->getId());
                    }
                    if (!$movimiento) {
                        $movimiento = $this->movimientoManager->create();
                        $movimiento->setNombre($newMovimientoDto->getNombre());
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
            $this->materialRepository->save($material);
            return [$material, null];
        }
        return [null, $form];
    }


}