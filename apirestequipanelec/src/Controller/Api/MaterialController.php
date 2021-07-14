<?php

namespace App\Controller\Api;

use Exception;
use Throwable;
use FOS\RestBundle\View\View;
use App\Service\Material\GetMaterial;
use App\Repository\MaterialRepository;
use App\Service\Material\DeleteMaterial;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Material\MaterialFormProcessor;
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
     * @Rest\Get(path="/material/{id}")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(
        string $id,
        GetMaterial $getMaterial
    ) {
        try {
            $material = ($getMaterial)($id);
        } catch (Exception $exception) {
            return View::create('Material no encontrado', Response::HTTP_BAD_REQUEST);
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
        [$material, $error] = ($materialFormProcessor)($request);
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
        MaterialFormProcessor $materialFormProcessor,
        Request $request
    ) {
        try {
            [$material, $error] = ($materialFormProcessor)($request, $id);
            $statusCode = $material ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $material ?? $error;
            return View::create($data, $statusCode);
        } catch (Throwable $t) {
            return View::create('Material no encontrado', Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @Rest\Delete(path="/material/{id}")
     * @Rest\View(serializerGroups={"material"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(
        string $id,
        GetMaterial $getMaterial,
        DeleteMaterial $deleteMaterial
    ) {
        try {
            ($deleteMaterial)($id);
        } catch (Throwable $t) {
            return View::create('Material no Encontrado', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }

}
