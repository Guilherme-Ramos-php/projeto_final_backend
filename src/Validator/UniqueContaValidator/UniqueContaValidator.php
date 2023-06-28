<?php

namespace App\Validator\UniqueContaValidator;

use App\Repository\ContaRepository;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueContaValidator extends ConstraintValidator
{

    public function __construct(
        private readonly ContaRepository $contaRepository
    )
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\UniqueConta $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $conta = $this->contaRepository->findOneBy(['conta' => $value]);

        if ($conta) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

    }
}