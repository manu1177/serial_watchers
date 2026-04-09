<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MovieRepository;
use App\Entity\Movie;
use App\Form\MovieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class MovieController extends AbstractController
{
    #[Route('/movies', name: 'app_movies')]
    public function index(MovieRepository $moviesRepository): Response
    {
        $movies = $moviesRepository->findAll();
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
            'movies' => $movies,
        ]);
    }

    #[Route('/movies/new', name: 'app_movies_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('app_movies');
        }

        return $this->render('movie/nouveau.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/movies/{id}', name: 'app_movie_detail')]
    public function detail(int $id, MovieRepository $repository): Response
    {
        $movie = $repository->find($id);
        if (!$movie) {
            throw $this->createNotFoundException('Ce film n\'existe pas.');
        }
        return $this->render('movie/detail.html.twig', [
            'movie' => $movie,
        ]);
    }
}
