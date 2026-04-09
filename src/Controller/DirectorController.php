<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\DirectorRepository;
use App\Entity\Director;
use App\Form\DirectorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class DirectorController extends AbstractController
{
    #[Route('/directors', name: 'app_directors')]

    public function index(DirectorRepository $directorsRepository): Response
    {
        $directors = $directorsRepository->findAll();
        return $this->render('director/index.html.twig', [
            'controller_name' => 'DirectorController',
            'directors' => $directors,
        ]);
    }

    #[Route('/directors/new', name: 'app_directors_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $director = new Director();
        $form = $this->createForm(DirectorType::class, $director);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($director);
            $em->flush();

            return $this->redirectToRoute('app_directors');
        }

        return $this->render('director/nouveau.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/directors/{id}', name: 'app_director_detail')]
    public function detail(int $id, DirectorRepository $repository): Response
    {
        $director = $repository->find($id);
        if (!$director) {
            throw $this->createNotFoundException('Ce Réalisateur n\'existe pas.');
        }
        return $this->render('director/detail.html.twig', [
            'director' => $director,
        ]);
    }
}
