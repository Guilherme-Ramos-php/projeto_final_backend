<?php

namespace App\Controller;

use App\DTO\FormErrorDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

class BaseController  extends AbstractController
{
    protected function getErrorsFromForm(FormInterface $form, $code = 400): FormErrorDTO
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $cause = $error->getCause();
            $errorIndex =
                $cause->getPropertyPath() ?
                    str_replace('data.', '', $cause->getPropertyPath()) :
                    $form->getName();
            $errors[$errorIndex][] = $error->getMessage();
        }

        foreach ($form as $child) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return new FormErrorDTO($code, 'Bad Request', $errors);
    }

}