<?php

namespace TinyUrl\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TinyUrl\MainBundle\Entity\Link;
use TinyUrl\MainBundle\Form\LinkType;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // Attention à la présention du code, et à rester constant

        //On crée au début de l'action l'appel/les appels au repository
        // On crée deux variables réutilisables
        $em = $this->get('doctrine')->getManager();
        $linkToRepo = $em->getRepository('TinyUrlMainBundle:Link');

        // Création du formulaire
        $form = $this->createForm(LinkType::class, new Link());

        // On récupère tous les liens de la BDD
        // Pour la scalabilité écrire une fonction qui limite aux 10 derniers liens
        // Ou les liens les plus populaires...
        $popularLinks = $linkToRepo->findPopularLinks();
        $lastAddedLinks = $linkToRepo->findLastAddedLinks();

        // Traitement du formulaire
        $link = new Link();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(false) {
                // Utiliser des types de messages flash réutilisable
                $this->addFlash('error', 'Le formulaire n\'est pas valide.');
                return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
            }

            $link = $form->getData();
            // On vérifie si l'URL long n'est pas déjà dans la BDD
            $isUrlAlreadyExisting = $linkToRepo->findOneBy([
                'longUrl'=>$link->getlongUrl()
            ]);
            // On vérifie si le ShortCode n'est pas déjà dans la BDD
            $isShortCodeAlreadyExisting = $linkToRepo->findOneBy([
                'shortCode'=>$link->getShortCode()
            ]);

            if ($isUrlAlreadyExisting !== null or $isShortCodeAlreadyExisting !== null ) {
                $this->addFlash('error', 'L\'url ou le ShortCode existent déjà dans la BDD.');
                return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
            }
            $em->persist($link);
            $em->flush();
            $this->addFlash('success', 'Un shortcode a été crée !');
        }

        // Bien présenter les données que l'on retourne avec la vue
        // pour ce soit facilement lisible
        return $this->render('TinyUrlMainBundle:Default:index.html.twig', [
                'form'=>$form->createView(),
                'popularLinks'=>$popularLinks,
                'lastAddedLinks'=>$lastAddedLinks
            ]);
    }



    public function redirectAction($shortcode) {
        // Toujours au début de l'action :
        // On fait appel au manager de Doctrine et on variabilise la/les repository/tories
        $em= $this->get('doctrine')->getManager();
        $linkToRepo = $em->getRepository('TinyUrlMainBundle:Link');

        // On récupère l'entité correspondant au paramètre
        $lien = $linkToRepo->findOneBy([
            'shortCode'=>$shortcode
        ]);

        if( !$lien) {
            // Si la base de donnée ne renvoie rien
            $this->addFlash('error', 'Ce shortcode n\'existe pas !');
            return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
        }

        // On incrémente le compteur du lien lorsque le shortCode est cliqué
        $linkToRepo->incrementCounter($lien);
        return $this->redirect($lien->getLongUrl(), 302);

    }

    public function deleteAction(Link $link) // Param converter
    {
        $em = $this->get('doctrine')->getManager();
        $em->remove($link);
        $em->flush();
        $this->addFlash('success', 'Le lien a été supprimé');
        return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
    }

    //    Ajax calls

    public function lastCommentAction() {
        $em = $this->get('doctrine')->getManager();
        $linkToRepo = $em->getRepository('TinyUrlMainBundle:Link');
        $lastComment = $linkToRepo->findLastComment();
        return $this->render('@TinyUrlMain/Default/Ajax/lastComment.html.twig', [
           'lastComment'=>$lastComment
        ]);
    }


    public function lastAddedAction() {
        $em = $this->get('doctrine')->getManager();
        $linkToRepo = $em->getRepository('TinyUrlMainBundle:Link');
        $lastAddedLinks = $linkToRepo->findLastAddedLinks();
        return $this->render('TinyUrlMainBundle:Default/Ajax:lastAddedLinks.html.twig', [
            'lastAddedLinks'=>$lastAddedLinks
        ]);

    }

    public function popularLinksAction() {
        $em = $this->get('doctrine')->getManager();
        $linkToRepo = $em->getRepository('TinyUrlMainBundle:Link');
        $popularLinks = $linkToRepo->findPopularLinks();
        return $this->render('TinyUrlMainBundle:Default/Ajax:popularLinks.html.twig', [
            'popularLinks'=>$popularLinks
        ]);
    }

}
