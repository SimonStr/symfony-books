<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Transliteration;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\BookFormType;


class BookController extends AbstractController
{
    /**
     * Matches /books exactly
     *
     * @Route("/books", name="books_list")
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user_id = $this->getUser()->getId();

        $entityManager = $this->getDoctrine()->getManager();
        $books = $entityManager->getRepository(Book::class)->findBy(['user_id' => $user_id]);


        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
        10
        );

        return $this->render('books/books_list.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Matches /book/*
     *
     * @Route("/book/{slug}", name="book_show")
     */
    public function show($slug)
    {
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $book = $repository->findOneBy(['slug' => $slug]);

        return $this->render('books/book_show.html.twig', ['book' => $book]);
    }

    /**
     * Matches /new/*
     *
     * @Route("/new/", name="new_book")
     */
    public function newBook(Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $book = new Book();
        $book->setUserId($this->getUser()->getId());

        $form = $this->createForm(BookFormType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = new Transliteration();
            $slug = $slug->transliterate($book->getName());
            $book->setSlug($slug);

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($book);
             $entityManager->flush();

            return $this->redirectToRoute('books_list');

        }
        return $this->render('books/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Matches /edit/*
     *
     * @Route("/edit/{id}", name="update_book")
     */
    public function updateBook($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book->isOwner($this->getUser())) {
            return $this->redirectToRoute('index');
        }
        $form = $this->createForm(BookFormType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager->persist($book);
            $entityManager->flush();


        }
        return $this->render('books/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Matches /delete/*
     *
     * @Route("/delete/{id}", name="delete_book")
     */
    public function deleteBook($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);
        if (!$book->isOwner($this->getUser())) {
            return $this->redirectToRoute('index');
        }
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->forward('App\Controller\BookController::list');

    }

}