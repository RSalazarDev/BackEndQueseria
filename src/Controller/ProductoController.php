<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;
use App\Security\JwtAuthenticator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Entity\Producto;

class ProductoController extends AbstractController
{
    /**
     * @Route("/productos", name="get_productos", methods={"GET"})
     */
    public function getproductos(): JsonResponse
    {
        
        $repo = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $repo->findAll();
        $data = [];
        foreach ($productos as $producto) {
            
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    
    
    
    /**
     * @Route("/productos/{id}", name="get_producto", methods={"GET"})
     */
    public function getproducto($id): JsonResponse
    {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->find($id);
        $data = [];
        
        if ($producto) {
            
        
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
            ];
            
            return new JsonResponse($data, Response::HTTP_OK);
        }
        

        return new JsonResponse(['error' => 'Producto inexistente ' . $id], Response::HTTP_NOT_FOUND);
    }
    
    /**
     * @Route("/productos", name="addproducto", methods={"POST"})
     */
    public function addproducto(Request $request): JsonResponse {
        
        $data = json_decode($request->getContent(), true);
        
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $precio = $data['precio'];
        

        if (empty($nombre)) {
            return new JsonResponse(['error' => 'No se pudo obtener'], Response::HTTP_PARTIAL_CONTENT);
        }

        $producto = new Producto();
        $producto->setNombre($nombre);
        $producto->setDescripcion($descripcion);
        $producto->setPrecio($precio);
        

        $em = $this->getDoctrine()->getManager();
        $em->persist($producto);
        $em->flush();

        return new JsonResponse(['respuesta' => 'Producto añadido'], Response::HTTP_OK);
    }
    
    /**
     * @Route("/productos/{id}", name="deletejuego", methods={"DELETE"})
     */
    public function deleteproducto($id): JsonResponse {
        $producto = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->find($id);
        if ($producto) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($producto);
            $em->flush();

            return new JsonResponse(['respuesta' => 'Producto borrado'], Response::HTTP_OK);
        }
        return new JsonResponse(['error' => 'No existe el producto ' . $id], Response::HTTP_NOT_FOUND);
    }
}
