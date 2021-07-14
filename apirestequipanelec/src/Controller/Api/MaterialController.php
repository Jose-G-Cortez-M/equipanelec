<?php


namespace App\Controller\Api;


use FOS\RestBundle\View\View;
use App\Service\MaterialManager;
use App\Service\MaterialFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Ramsey\Uuid\Uuid;


class MaterialController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/material")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        MaterialManager $materialManager
    ) {
        return $materialManager->getRepository()->findAll();
    }
    /**
     * @Rest\Get(path="/material/{id}")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(
        string $id,
        MaterialManager $materialManager
    ) {
        $material = $materialManager->find(Uuid::fromString($id));
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
        MaterialManager $materialManager,
        MaterialFormProcessor $materialFormProcessor,
        Request $request
    ) {
        $material = $materialManager->create();
        [$material, $error] = ($materialFormProcessor)($material, $request);
        $statusCode = $material ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $material ?? $error;
        return View::create($data, $statusCode);
    }
    
    /**
     * @Rest\Post(path="/material/{id}")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        MaterialManager $materialManager,
        MaterialFormProcessor $materialFormProcessor,
        Request $request
    ) {
        $material = $materialManager->find(Uuid::fromString($id));
        if (!$material) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        [$material, $error] = ($materialFormProcessor)($material, $request);
        $statusCode = $material ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $material ?? $error;
        return View::create($data, $statusCode);

    }
    /**
     * @Rest\Delete(path="/material/{id}")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(
        string $id,
        MaterialManager $materialManager
    ) {
        $material = $materialManager->find(Uuid::fromString($id));
        if (!$material) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        $materialManager->delete($material);
        return View::create(null, Response::HTTP_NO_CONTENT);
    }

}
