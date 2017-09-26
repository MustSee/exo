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
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(false) {
                // Utiliser des types de messages flash réutilisable
                $this->addFlash('error', 'Ce lien existe déjà');
                return $this->redirect($this->generateUrl('tiny_url_main_homepage'));
            }
            $em->persist($form->getData());
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
            return $this->redirect($lien->getLongUrl(), 302);

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
