<?php

namespace App\Controller;

use App\DataTransformer\ContaDescricaoInputTransformer;
use App\DataTransformer\ContaInputTransformer;
use App\DataTransformer\ContaOutputTransformer;
use App\DataTransformer\ExtratoOutputTransformer;
use App\DTO\ContasDTO;
use App\Entity\Conta;
use App\Entity\Pessoa;
use App\Form\ContaType;
use App\Repository\ContaRepository;
use App\Utils\ContaUtil;
use App\Utils\CpfUtils;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/conta')]
class ContaController extends BaseController
{

    public function __construct(
        private readonly ContaRepository $contasRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer
    )
    {
    }

    #[Route('/all/{pessoaId}', name:'get_all', methods: ['GET'])]
    public function getAllAction(int $pessoaId)
    {
        try {
            $pessoa = $this->entityManager->getRepository(Pessoa::class)->find($pessoaId);
            if (!$pessoa){
                throw new \Exception('Pessoa Não Existe');
            }
            return new JsonResponse(
                $this->serializer->serialize( $pessoa->getContas(),'json'),
                Response::HTTP_OK,
                json:true
            );
        }catch (\Exception $e){
            return new JsonResponse(
                $this->serializer->serialize( $e->getMessage(),'json',[DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']),
                Response::HTTP_NOT_FOUND,
                json:true
            );
        }
    }
    #[Route('/{conta}', name: 'get_conta',methods: ['GET'])]
    public function getAction(
        string $conta,
        ContaOutputTransformer $contaOutputTransformer
    ): Response
    {
        try {
            $contaResult = $this->contasRepository->findBy(['conta'=> $conta]);
            if ($contaResult) {
                return new JsonResponse(
                    $this->serializer->serialize( $contaOutputTransformer->transform($contaResult),'json'),
                    Response::HTTP_OK,
                    json:true
                );
            }

            return new JsonResponse(
                $this->serializer->serialize( 'Nenhum Registro foi encontrado','json'),
                Response::HTTP_OK,
                json:true
            );

        } catch (\Exception $e) {
            return new JsonResponse(
                $this->serializer->serialize( "Não Foi possivel buscar Conta, Detalhe: ".$e->getMessage(),'json'),
                Response::HTTP_NOT_FOUND,
                json:true
            );
        }
    }

    #[Route('/gerar_conta', name: 'post_conta', methods: ['POST'])]
    public function postAction(
        Request $request,
        ContaInputTransformer $contaInputTransformer,

    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $data['conta'] = ContaUtil::generateAccountNumber();
        $contasDTO = new ContasDTO();

        $form = $this->createForm(
            ContaType::class,
            $contasDTO,
            [
                'validation_groups' => ['post']
            ]
        );

        $form->submit($data);


        if ($form->isValid()) {
            $conta = $contaInputTransformer->transform($contasDTO);

            return new JsonResponse(
               $this->serializer->serialize( $conta,'json'),
                Response::HTTP_OK,
                [],
                true
            );
        }

        return new JsonResponse(
            $this->serializer->serialize( $this->getErrorsFromForm($form, Response::HTTP_CONFLICT),'json'),
            Response::HTTP_CONFLICT
        );
    }

    #[Route('/edit/{id}', name:'put_conta', methods: ['PUT'])]
    public function putAction(
        Request $request,
        Conta $conta,
        ContaDescricaoInputTransformer $contaDescricaoInputTransformer
    ) :JsonResponse {

        $data = json_decode($request->getContent(), true);

        $contasDTO = new ContasDTO();

        $form = $this->createForm(
            ContaType::class,
            $contasDTO,
            [
                'validation_groups' => ['put']
            ]
        );

        $form->submit($data);

        try {

            if ($form->isValid()) {
                $conta = $contaDescricaoInputTransformer->transform($contasDTO,$conta );

                return new JsonResponse(
                    $this->serializer->serialize( $conta,'json'),
                    Response::HTTP_OK,
                    [],
                    true
                );
            }

            return new JsonResponse(
                $this->serializer->serialize( $this->getErrorsFromForm($form, Response::HTTP_CONFLICT),'json'),
                Response::HTTP_CONFLICT
            );
        }catch (\Exception $e){
            return new JsonResponse(
                $this->serializer->serialize( $e->getMessage(),'json'),
                Response::HTTP_CONFLICT
            );
        }

    }

    #[Route('/{conta}', name: 'delete_conta', methods: ['DELETE'])]
    public function deleteAction(
        string $conta,
    ): JsonResponse {
        $contaResult = $this->contasRepository->findOneBy(['conta'=>$conta]);
        if ($contaResult) {
            $this->entityManager->remove($contaResult);
            $this->entityManager->flush();
            return new JsonResponse(
                $this->serializer->serialize( 'Removido com sucesso','json'),
                Response::HTTP_OK,
                [],
                true
            );
        }
        return new JsonResponse(
            $this->serializer->serialize( 'Não foi possível remover o Conta','json'),
            Response::HTTP_BAD_REQUEST,
            [],
            true
        );
    }


    #[Route('/conta/{conta}/extrato', name: 'extrato_conta', methods: ['GET'])]
    public function getExtrato(
        string $conta,
        ExtratoOutputTransformer $extratoOutputTransformer
    ) :JsonResponse
    {
        try {
            $contaResult = $this->contasRepository->findOneBy(['conta'=> $conta]);
            if ($contaResult) {
                return new JsonResponse(
                    $this->serializer->serialize( $extratoOutputTransformer->transform($contaResult),'json'),
                    Response::HTTP_OK,
                    json:true
                );
            }

            return new JsonResponse(
                $this->serializer->serialize( 'Nenhum Registro foi encontrado','json'),
                Response::HTTP_OK,
                json:true
            );

        } catch (\Exception $e) {
            return new JsonResponse(
                $this->serializer->serialize( "Não Foi possivel buscar Extrato, Detalhe: ".$e->getMessage(),'json'),
                Response::HTTP_NOT_FOUND,
                json:true
            );
        }
    }

}