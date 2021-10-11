<?php

namespace App\Controller;

use App\Entity\Infos;
use App\Entity\Membre;
use App\Form\InfosType;
use App\Form\MembreType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    //Page accueil du site //
    //----------- ----------//
    /**
     * @Route ("/", name="main_accueil")
     */
    public function accueil (MembreRepository $membreRepository) {

        $membres = $membreRepository->getLastSuscbribers();
        return $this->render('accueil.html.twig', ["membres"=> $membres]);
    }


    /**
     * @Route ("/inscription", name="main_inscription")
     */
    public function inscription (EntityManagerInterface $entityManager, Request $request) : Response
    {
        $membre = new Membre();
        $membre->setDateAdded(new \DateTime());

        $membreForm = $this->createForm(MembreType::class, $membre);

        $membreForm->handleRequest($request);

        if ($membreForm->isSubmitted() && $membreForm->isValid()) {
            $entityManager->persist($membre);
            $entityManager->flush();

            $this->addFlash('succes', "Membre ajouté");

            return $this->redirectToRoute('main_profilFill', ["id" => $membre->getId()]);
        }

            return $this->render('inscription.html.twig', ["membreForm" => $membreForm->createView()]);
    }



    /**
     * @Route ("/profil/{id}", name="main_profil")
     */
    public function consultProfil (int $id, MembreRepository $membreRepository) {

        $membre = $membreRepository ->findOneBy(['id' => $id]);
        if(!$membre) {
            throw $this->createNotFoundException('This member is a ghost !');
        }
        return $this->render('profil.html.twig', ["membre" => $membre]);
    }


    /**
     * @Route ("/profilFill", name="main_profilFill")
     */
    public function fillProfil(Request $request, EntityManagerInterface $entityManager) {
        $info = new Infos();
        $infoForm = $this->createForm(InfosType::class, $info);

        $infoForm->handleRequest($request);

        if($infoForm->isSubmitted() && $infoForm->isValid()) {
            $entityManager->persist($info);
            $entityManager->flush();

            $this->addFlash('succes', "Profil complet, Yeah!");
        }

        return $this->render('profilFill.html.twig', ["infosForm" => $infoForm->createView()]);

    }

}