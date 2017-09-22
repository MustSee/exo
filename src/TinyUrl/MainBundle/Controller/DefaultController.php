<?php

namespace TinyUrl\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TinyUrl\MainBundle\Entity\Link;
use TinyUrl\MainBundle\Form\LinkType;
use TinyUrl\MainBundle\Form\FormHandler;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

        // Création du formulaire
        $form = $this->createForm(LinkType::class, new Link());

        // On récupère tous les liens de la BDD
        $links = $this->get('doctrine')
            ->getRepository('TinyUrlMainBundle:Link')
            ->findAll();

        $datas = ['form'=>$form->createView(), 'links'=>$links];

        // Appel à la BDD lorsque le formulaire est validé
        $em = $this->get('doctrine')->getManager();
        $formHandler = new FormHandler($form, $request, $em);
        if ($formHandler->process()) {
            $this->addFlash('success', 'Un shortCode a été crée !');
            return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
        }

        return $this->render('TinyUrlMainBundle:Default:index.html.twig', $datas);
    }


    public function redirectAction($shortcode) {
        // On récupère l'entité correspondant au paramètre
        $lien = $this->get('doctrine')
            ->getRepository('TinyUrlMainBundle:Link')
            ->findOneBy(array('shortCode'=>$shortcode));


        if($lien) {
            // On incrémente le compteur du lien lorsque le shortCode est cliqué
            $em = $this->get('doctrine')->getManager();
            $counter = $lien->getCounter();
            $lien->setCounter($counter + 1);
            $em->flush();
            return $this->redirect($lien->getLongUrl(), 301);

        } else {

            // Si la base de donnée ne renvoie rien
            $this->addFlash('noMatchFound', 'Ce shortcode n\'existe pas !');
            return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
        }
    }

    public function deleteAction(Link $link) // Param converter
    {
        $em = $this->get('doctrine')->getManager();
        $em->remove($link);
        $em->flush();
        $this->addFlash('success', 'Le lien a été supprimé');
        return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
    }
}
