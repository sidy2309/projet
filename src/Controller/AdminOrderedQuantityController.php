<?php

namespace App\Controller;

use App\Entity\OrderedQuantity;
use App\Form\OrderedQuantityType;
use App\Repository\OrderedQuantityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/ordered/quantity")
 */
class AdminOrderedQuantityController extends AbstractController
{
    /**
     * @Route("/", name="admin_ordered_quantity_index", methods={"GET"})
     */
    public function index(OrderedQuantityRepository $orderedQuantityRepository): Response
    {
        return $this->render('admin_ordered_quantity/index.html.twig', [
            'ordered_quantities' => $orderedQuantityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_ordered_quantity_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $orderedQuantity = new OrderedQuantity();
        $form = $this->createForm(OrderedQuantityType::class, $orderedQuantity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orderedQuantity);
            $entityManager->flush();

            return $this->redirectToRoute('admin_ordered_quantity_index');
        }

        return $this->render('admin_ordered_quantity/new.html.twig', [
            'ordered_quantity' => $orderedQuantity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_ordered_quantity_show", methods={"GET"})
     */
    public function show(OrderedQuantity $orderedQuantity): Response
    {
        return $this->render('admin_ordered_quantity/show.html.twig', [
            'ordered_quantity' => $orderedQuantity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_ordered_quantity_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OrderedQuantity $orderedQuantity): Response
    {
        $form = $this->createForm(OrderedQuantityType::class, $orderedQuantity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_ordered_quantity_index');
        }

        return $this->render('admin_ordered_quantity/edit.html.twig', [
            'ordered_quantity' => $orderedQuantity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_ordered_quantity_delete", methods={"POST"})
     */
    public function delete(Request $request, OrderedQuantity $orderedQuantity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderedQuantity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orderedQuantity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_ordered_quantity_index');
    }
}
