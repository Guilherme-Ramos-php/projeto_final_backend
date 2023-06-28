<?php

namespace App\Controller;

use App\DataTransformer\MovimentacaoInputTransformer;
use App\DTO\MovimentacoesDTO;
use App\Form\MovimentacoesType;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class MovimentacaoController extends BaseController
{
    #[Route('/movimentacao', name: 'post_movimentacao', methods: ['POST'])]
    public function postAction(
        Request $request,
        MovimentacaoInputTransformer $movimentacaoInputTransformer,
        SerializerInterface $serializer
    ) :JsonResponse {

        try {


            $data = json_decode($request->getContent(), true);

            $data['acao'] = $data['valor'] > 0 ? 'depositar' : 'sacar';
            $movimentacaoDTO = new MovimentacoesDTO();

            $form = $this->createForm(
                MovimentacoesType::class,
                $movimentacaoDTO,
                [
                    'validation_groups' => ['post']
                ]
            );

            $form->submit($data);

            if ($form->isValid()) {
                $conta = $movimentacaoInputTransformer->transform($movimentacaoDTO);
                return new JsonResponse(
                    $serializer->serialize($conta, 'json'),
                    Response::HTTP_OK,
                    [],
                    true
                );
            }

            return new JsonResponse(
                $this->getErrorsFromForm($form, Response::HTTP_CONFLICT),
                Response::HTTP_CONFLICT
            );
        }catch (\Exception $e) {
            return new JsonResponse(
                $e->getMessage(),
              Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}