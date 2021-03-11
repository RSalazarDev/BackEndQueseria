<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Usuario;
use DateTime;
use Firebase\JWT\JWT;

class UsuariosController extends AbstractController
{
    
    
    /**
     * @Route("/usuarios/login", name="login", methods={"POST"})
     */
    public function login(Request $request) {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $pwd = $data['password'];

        if ($email && $pwd) {
            $usuario = $this->getDoctrine()
                    ->getRepository(Usuario::class)
                    ->findOneBy(['email' => $email]);

            if ($usuario) {
                if (password_verify($pwd, $usuario->getPassword())) {
                    $payload = [
                        "usuario" => $usuario->getEmail(),
                        "exp" => (new \DateTime())->modify("+3 day")->getTimestamp()
                    ];

                    $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
                    $data = [
                        'respuesta' => 'Se ha iniciado sesion',
                        'token' => $jwt
                    ];

                    return new JsonResponse($data, Response::HTTP_OK);
                }
                return new JsonResponse(['error' => 'Credenciales inválidas'], Response::HTTP_NOT_FOUND);
            }
            return new JsonResponse(['error' => 'Credenciales inválidas'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['error' => 'Faltan parámetros'], Response::HTTP_PARTIAL_CONTENT);
    }
    
    
    
    
    /**
     * @Route("/usuarios/registro", name="registro", methods={"POST"})
     */
    public function registrar(Request $request) {
        $data = json_decode($request->getContent(), true);
      
        $email = $data['email'];
        $password = $data['password'];
        $nombre = $data['nombre'];
        $telefono = $data['telefono'];
        $fechaCreacion = new DateTime();
        
        if (empty($email)||empty($password)||empty($nombre)) {
            return new JsonResponse(['error' => 'Faltan parámetros'], Response::HTTP_PARTIAL_CONTENT);
        }

        $usuario = new Usuario();
        $usuario->setEmail($email);
        $usuario->setNombre($nombre);
        $usuario->setTelefono($telefono);
        $usuario->setFechacreacion($fechaCreacion);
        $usuario->setPassword(password_hash($password, PASSWORD_BCRYPT));

        
        $em = $this->getDoctrine()->getManager();
        $em->persist($usuario);
        $em->flush();
        return new JsonResponse(['respuesta' => 'Usuario añadido'], Response::HTTP_OK);
    }
    
    
}
