<?php


namespace App\Controller\Api;

use App\Entity\Material;
use App\Form\Model\MaterialDto;
use App\Form\Type\MaterialFormType;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use League\Flysystem\FilesystemOperator;

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
        FilesystemOperator $defaultStorage
    ) {
        $materialDto = new MaterialDto();
        $form = $this->createForm(MaterialFormType::class, $materialDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $extension = explode('/', mime_content_type($materialDto->base64Imagen))[1];
            $data = explode(',', $materialDto->base64Imagen);
            $filename = sprintf('%s.%s', uniqid('material_', true), $extension);
            $defaultStorage->write($filename, base64_decode($data[1]));
            $material = new Material();
            $material->setNombre($materialDto->nombre);
            $material->setImagen($filename);
            $em->persist($material);
            $em->flush();
            return $material;
        }
        return $form;
    }
}
