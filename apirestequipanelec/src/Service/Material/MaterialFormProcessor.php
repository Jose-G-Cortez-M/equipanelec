<?php

namespace App\Service\Material;


use App\Entity\Material;
use App\Service\FileUploader;
use App\Form\Model\MaterialDto;
use App\Form\Model\MovimientoDto;
use App\Form\Type\MaterialFormType;
use App\Repository\MaterialRepository;
use App\Service\Movimiento\GetMovimiento;
use App\Service\Movimiento\CreateMovimiento;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;

Class MaterialFormProcessor
{

    private GetMaterial $getMaterial;
    private MaterialRepository $materialRepository;
    private CreateMovimiento $createMovimiento;
    private GetMovimiento $getMovimiento;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;

    public function __construct(
        GetMaterial $getMaterial,
        MaterialRepository $materialRepository,
        GetMovimiento $getMovimiento,
        CreateMovimiento $createMovimiento,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory
    ) {
        $this->getMaterial = $getMaterial;
        $this->materialRepository = $materialRepository;
        $this->createMovimiento = $createMovimiento;
        $this->getMovimiento = $getMovimiento;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, ?string $materialId = null): array
    {
        $material = null;
        $materialDto = null;
        /** @var MovimientoDto[]|ArrayCollection */
        $originalMovimientos = new ArrayCollection();
        if ($materialId === null) {
            $material = Material::create();
            $materialDto = MaterialDto::createEmpty();
        } else {
            $material = ($this->getMaterial)($materialId);
            $materialDto = MaterialDto::createFromMaterial($material);
            foreach ($material->getMovimientos() as $movimiento) {
                $movimientoDto = MovimientoDto::createFromMovimiento($movimiento);
                $materialDto->movimientos[] = $movimientoDto;
                $originalMovimientos->add($movimientoDto);
            }
        }

        $form = $this->formFactory->create(MaterialFormType::class, $materialDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'El formulario no se pudo enviar'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        // Remove Movimientos
        foreach ($originalMovimientos as $originalMovimientoDto) {
            if (!\in_array($originalMovimientoDto, $materialDto->movimientos)) {
                $movimiento = ($this->getMovimiento)($originalMovimientoDto->getId());
                $material->removeMovimiento($movimiento);
            }
        }

        // Add Movimientos
        foreach ($materialDto->getMovimientos() as $newMovimientoDto) {
            if (!$originalMovimientos->contains($newMovimientoDto)) {
                $movimiento = null;
                if ($newMovimientoDto->getId() !== null) {
                    $movimiento = ($this->getMovimiento)($newMovimientoDto->getId());
                }
                if (!$movimiento) {
                    $movimiento = ($this->createMovimiento)($newMovimientoDto->getNombre());
                }
                $material->addMovimiento($movimiento);
            }
        }
        $material->setNombre($materialDto->nombre);
        if ($materialDto->getBase64Imagen()) {
            $filename = $this->fileUploader->uploadBase64File($materialDto->getBase64Imagen());
            $material->setImagen($filename);
        }
        $this->materialRepository->save($material);
        return [$material, null];
    }
}