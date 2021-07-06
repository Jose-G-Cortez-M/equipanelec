<?php

namespace App\Controller;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MaterialController extends AbstractController
{
    /**
     * @Route("/material/list", name="material_list")
     */
    public function list(Request $request, MaterialRepository $materialRepository){
        $material = $materialRepository->findAll();
        $materialAsArray = [];
        foreach ($material as $material) {
            $materialAsArray[] = [
                'id' => $material->getId(),
                'nombre' => $material->getNombre(),
                'imagen' => $material->getImagen()
            ];
        };
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => $materialAsArray
        ]);
        return $response;
    }   
    /**
     * @Route("/material/crear", name="crear_material")
     */
    public function createMaterial(Request $request, EntityManagerInterface $em){
        $material = new Material();
        $response = new JsonResponse();
        $nombre = $request->get('nombre', null);
        if (empty($title)) {
            $response->setData([
                'success' => false,
                'error' => 'Title cannot be empty',
                'data' => null
            ]);
            return $response;
        }
        $material->setNombre($nombre);
        $em->persist($material);
        $em->flush();
        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => $material->getId(),
                    'nombre' => $material->getNombre()
                ]
            ]
        ]);
        return $response;
        
    }
}