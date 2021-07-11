<?php


namespace App\Controller\Api;

use App\Entity\Material;
use App\Entity\Movimiento;
use App\Service\FileUploader;
use App\Form\Model\MaterialDto;
use App\Form\Model\MovimientoDto;
use App\Form\Type\MaterialFormType;
use App\Repository\MaterialRepository;
use App\Repository\MovimientoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;


class MaterialController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/material")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        MaterialRepository $materialRepository
    ) {
        return $materialRepository->findAll();
    }

    /**
     * @Rest\Post(path="/material")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        EntityManagerInterface $em,
        Request $request,
        FileUploader $fileUploader
    ) {
        $materialDto = new MaterialDto();
        $form = $this->createForm(MaterialFormType::class, $materialDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if ($form->isValid()) {
            $material = new Material();
            $material->setNombre($materialDto->nombre);
            if ($materialDto->base64Imagen) {
                $filename = $fileUploader->uploadBase64File($materialDto->base64Imagen);
                $material->setImagen($filename);
            }
            $em->persist($material);
            $em->flush();
            return $material;
        }
        return $form;
    }
    
    /**
     * @Rest\Post(path="/material/{id}")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        EntityManagerInterface $em,
        MaterialRepository $materialRepository,
        MovimientoRepository $movimientoRepository,
        Request $request,
        FileUploader $fileUploader
    ) {
        $material = $materialRepository->find($id);
        if (!$material) {
            throw $this->createNotFoundException('Material not found');
        }

        $materialDto = MaterialDto::createFromMaterial($material);
        $originalMovimientos = new ArrayCollection();

        foreach ($material->getMovimientos() as $movimiento) {
            $movimientoDto = MovimientoDto::createFromMovimiento($movimiento);
            $materialDto->movimientos[] = $movimientoDto;
            $originalMovimientos->add($movimientoDto);
        }

        $form = $this->createForm(MaterialFormType::class, $materialDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if ($form->isValid()) {
            // Remove movimientos
            foreach ($originalMovimientos as $originalMovimientoDto) {
                if (!in_array($originalMovimientoDto, $materialDto->movimientos)) {
                    $movimiento = $movimientoRepository->find($originalMovimientoDto->id);
                    $material->removeMovimiento($movimiento);
                }
            }

            // Add movimientos
            foreach ($materialDto->movimientos as $newMovimientoDto) {
                if (!$originalMovimientos->contains($newMovimientoDto)) {
                    $movimiento = $movimientoRepository->find($newMovimientoDto->id ?? 0);
                    if (!$movimiento) {
                        $movimiento = new Movimiento();
                        $movimiento->setNombre($newMovimientoDto->nombre);
                        $em->persist($movimiento);
                    }
                    $material->addMovimiento($movimiento);
                }
            }
            $material->setNombre($materialDto->nombre);
            if ($materialDto->base64Imagen) {
                $filename = $fileUploader->uploadBase64File($materialDto->base64Imagen);
                $material->setImagen($filename);
            }
            $em->persist($material);
            $em->flush();
            $em->refresh($material);
            return $material;
        }
        return $form;
    }
}
