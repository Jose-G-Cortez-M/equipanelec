<?php


namespace App\Controller\Api;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use App\Service\Material\DeleteMaterial;
use App\Service\Material\GetMaterial;
use FOS\RestBundle\View\View;
use App\Service\MaterialFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Throwable;

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
     * @Rest\Get(path="/material/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(
        int $id,
        GetMaterial $getMaterial
    ) {

        $material = ($getMaterial)($id);
        if(!$material){
            return View::create('Material no Encontrado', Response::HTTP_BAD_REQUEST);
        }
        return $material;
    }

    /**
     * @Rest\Post(path="/material")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        MaterialFormProcessor $materialFormProcessor,
        Request $request
    ) {
        $material = Material::create();
        [$material, $error] = ($materialFormProcessor)($material, $request);
        $statusCode = $material ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $material ?? $error;
        return View::create($data, $statusCode);
    }
    
    /**
     * @Rest\Post(path="/material/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        GetMaterial $getMaterial,
        MaterialFormProcessor $materialFormProcessor,
        Request $request
    ) {
        $material = ($getMaterial)($id);
        if (!$material) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        [$material, $error] = ($materialFormProcessor)($material, $request);
        $statusCode = $material ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $material ?? $error;
        return View::create($data, $statusCode);

    }
    /**
     * @Rest\Delete(path="/material/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(
        int $id,
        DeleteMaterial $deleteMaterial
    ) {
        try{
            ($deleteMaterial)($id);
        } catch (Throwable $t){
            return View::create('Material no encontrado', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }

}
