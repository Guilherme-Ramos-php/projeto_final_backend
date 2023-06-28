<?php

namespace App\EventListener;

use App\Entity\Conta;
use App\Entity\Pessoa;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
class LoginSuccessListener
{
    public function onLoginSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();
        $payload = $event->getData();
        if (!$user instanceof Pessoa) {
            return;
        }

        $arrayContas = [];
        if ($user->getContas()){
            foreach ($user->getContas() as $key => $conta){
                $arrayContas[$key+1] = ['conta'=> $conta->getConta(), 'data_criacao' => $conta->getDataCriacao()->format('d/m/Y H:i')];
            }
        }
        $payload['user'] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getNome(),
            'cpf' => $user->getCpf(),
            'contas' => $arrayContas
        ];

        $event->setData($payload);
    }
}