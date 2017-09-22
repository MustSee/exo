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

            return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
        }

        return $this->render('TinyUrlMainBundle:Default:index.html.twig', $datas);
    }


    public function redirectAction($shortcode) {
        $longUrl = $this->get('doctrine')
            ->getRepository('TinyUrlMainBundle:Link')
            ->findOneBy(array('shortCode'=>$shortcode));

        if($longUrl) {
            $em = $this->get('doctrine')->getManager();
            $linkToModify = $em->getRepository(Link::class)->findOneBy(array('shortCode'=>$shortcode));
            $counter = $linkToModify->getCounter();
            $linkToModify->setCounter(++$counter);
            $em->persist($linkToModify);
            $em->flush();

            return $this->redirect($longUrl->getLongUrl(), 301);
        }
        // Si la base de donnée ne renvoie rien
        else  {
            $this->addFlash('noMatchFound', 'Ce shortcode n\'existe pas !');
            return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
        }

//        $this->get('doctrine')
//            ->getRepository('TinyUrlMainBundle:Link')
//            ->updateCounter();


    }
}
