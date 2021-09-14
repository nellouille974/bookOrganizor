<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookSearch;
use App\Form\BookType;
use App\Form\BookSearchType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository, PaginatorInterface $paginator, Request $request ): Response
    {
        
        $search = new BookSearch();
        $form = $this->createForm(BookSearchType::class, $search);
        $form->handleRequest($request);

        $books = $paginator->paginate(
            $bookRepository->findAllBook($search),
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'current_menu' => 'current',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Livre ajouté avec succès');

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
            'current_menu' => 'current',
        ]);
    }

    /**
     * @Route("/{slug}-{id}", name="book_show", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET"})
     */
    public function show(Book $book, string $slug): Response
    {
        if($book->getSlug() !== $slug) {
            return $this->redirectToRoute('book_show', [
                'id' => $book->getId(),
                'slug' => $book->getSlug(),
                ], 301);
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'current_menu' => 'current',
        ]);
    }

    /**
     * @Route("/{slug}-{id}/edit", name="book_edit", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book, string $slug): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('success', 'Livre modifié avec succès');

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        if($book->getSlug() !== $slug) {
            return $this->redirectToRoute('book_index', [
                'id' => $book->getId(),
                'slug' => $book->getSlug(),
                ], 301);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
            'current_menu' => 'current',
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"POST"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();

            $this->addFlash('success', 'Livre supprimé avec succès');
        }
        

        return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
    }
}
