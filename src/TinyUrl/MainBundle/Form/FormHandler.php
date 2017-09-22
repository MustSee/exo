<?php

namespace TinyUrl\MainBundle\Form;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class FormHandler // Cette classe fonctionne pour tous les formulaires
{
    private $form; // Notre formulaire
    private $request; // La superglobale
    private $em; // Entité manager de doctrine

    public function __construct(Form $form, Request $request, EntityManager $em)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
    }

    public function process() {
        if($this->request->getMethod() == "POST") {
            $this->form->handleRequest($this->request); //hydrate l'instance
            if($this->form->isValid() == true) {
                // On persiste les données
                $this->onSuccess($this->form->getData());
                // On return true
                return true;
            }
            return false;
        }
        return false;
    }

    private function onSuccess($instance) {
        $this->em->persist($instance);
        $this->em->flush();
    }
}