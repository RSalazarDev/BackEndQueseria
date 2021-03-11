<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PedidoController extends AbstractController
{
      /**
     * @Route("/pedidos", name="crear_pedido", methods={"POST"})
     */
    public function crearpedido(Request $request, ParameterBagInterface $params, UserProviderInterface $userProvider): JsonResponse {
        $em = $this->getDoctrine()->getManager();
        $auth = new JwtAuthenticator($em, $params);

        $credentials = $auth->getCredentials($request);

        $user = $auth->getUser($credentials, $userProvider);

        if ($user) {
            $data = json_decode($request->getContent(), true);
            $preciopedido = $data['precio_pedido'];

            if (empty($fechapedido)) {
                return new JsonResponse(['error' => 'Faltan parámetros'], Response::HTTP_PARTIAL_CONTENT);
            }

            $pedido = new Pedido();

            
            $pedido->setPrecio($preciopedido);
            $pedido->setIdUsuario($user);
            $em->persist($lista);
            $em->flush();

            return new JsonResponse(['respuesta' => 'Pedido creado'], Response::HTTP_OK);
        }
        return new JsonResponse(['error' => 'Usuario no logueado'], Response::HTTP_UNAUTHORIZED);
    }
    
    /**
     * @Route("/pedidos", name="get_pedidos", methods={"GET"})
     */
    public function getpedidosUsuario(Request $request, ParameterBagInterface $params, UserProviderInterface $userProvider): JsonResponse {
        $em = $this->getDoctrine()->getManager();
        $auth = new JwtAuthenticator($em, $params);

        $credentials = $auth->getCredentials($request);

        $user = $auth->getUser($credentials, $userProvider);

        if ($user) {

            $data = $this->getPedidos($user);
            return new JsonResponse($data, Response::HTTP_OK);
        }

        return new JsonResponse(['error' => 'Usuario no logueado'], Response::HTTP_UNAUTHORIZED);
    }
    
    
    /**
     * @Route("/pedidos/{id}", name="get_pedido", methods={"GET"})
     */
    public function verpedido($id, Request $request, ParameterBagInterface $params, UserProviderInterface $userProvider): JsonResponse {
        $em = $this->getDoctrine()->getManager();
        $auth = new JwtAuthenticator($em, $params);

        $credentials = $auth->getCredentials($request);

        $usuario = $auth->getUser($credentials, $userProvider);

        if ($usuario) {
            $data = $this->getLineasPedido($this->getDoctrine()->getRepository(Pedido::class)->find($id));

            return new JsonResponse($data, Response::HTTP_OK);
        }
        return new JsonResponse(['error' => 'Usuario no logueado'], Response::HTTP_UNAUTHORIZED);
    }
    
    
    //See encarga de recuperar todas las lineas pedido pertenecientes al pedido
    private function getLineasPedido(Pedido $pedido) {
        $lineas = $pedido->getLineaPedidos();
        
        $productos=[];
        
        foreach ($lineas as $linea) {
            $producto->$linea->getIdProducto();
            
            
            $productos[] = [
                    'id' => $producto->getId(),
                    'nombre' => $producto->getNombre(),
                    'descripcion' => $producto->getDescripcion(),
                    'precio' => $producto->getPrecio()
                ];
            
        }
        //En lugar de devolver las lineas de pedido, devuelvo los productos de ese pedido directamente para mostrarselo al cliente
        
        
        
        return $productos;
    }
    
}
