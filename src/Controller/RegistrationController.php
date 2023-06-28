<?php

namespace App\Controller;

use App\Entity\Pessoa;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/api')]
class RegistrationController extends AbstractController
{

    #[Route('/register', name: 'app_registration', methods: ['POST'])]
    public function index(
        ManagerRegistry $doctrine,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
    ): Response
    {

        try {
            $em = $doctrine->getManager();
            $decoded = json_decode($request->getContent());
            $email = $decoded->email;
            $plaintextPassword = $decoded->password;

            $pessoa = new Pessoa();
            $hashedPassword = $passwordHasher->hashPassword(
                $pessoa,
                $plaintextPassword
            );
            $pessoa->setCpf($decoded->cpf);
            $pessoa->setNome($decoded->name);
            $pessoa->setDataNascimento(DateTime::createFromFormat('d/m/Y',$decoded->bourned));
            $pessoa->setPassword($hashedPassword);
            $pessoa->setEmail($email);
            $pessoa->setDataCriacao(new DateTime());
            $em->persist($pessoa);
            $em->flush();
            return new JsonResponse(['message' => 'Registered Successfully'],Response::HTTP_OK);
        }catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
