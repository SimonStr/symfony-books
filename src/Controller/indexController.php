<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class indexController extends AbstractController
{
    /**
     * Matches / exactly
     *
     * @Route("/", name="index")
     */
    public function indexAction(Request $request, PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $books = $repository->findAll();

        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('index.html.twig', ['pagination' => $pagination]);
    }
}