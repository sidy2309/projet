<?php

namespace App\Controller;

use App\Entity\PaymentRequest;
use App\Form\PaymentRequestType;
use App\Repository\PaymentRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/payment/request")
 */
class AdminPaymentRequestController extends AbstractController
{
    /**
     * @Route("/", name="admin_payment_request_index", methods={"GET"})
     */
    public function index(PaymentRequestRepository $paymentRequestRepository): Response
    {
        return $this->render('admin_payment_request/index.html.twig', [
            'payment_requests' => $paymentRequestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_payment_request_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $paymentRequest = new PaymentRequest();
        $form = $this->createForm(PaymentRequestType::class, $paymentRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paymentRequest);
            $entityManager->flush();

            return $this->redirectToRoute('admin_payment_request_index');
        }

        return $this->render('admin_payment_request/new.html.twig', [
            'payment_request' => $paymentRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_payment_request_show", methods={"GET"})
     */
    public function show(PaymentRequest $paymentRequest): Response
    {
        return $this->render('admin_payment_request/show.html.twig', [
            'payment_request' => $paymentRequest,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_payment_request_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PaymentRequest $paymentRequest): Response
    {
        $form = $this->createForm(PaymentRequestType::class, $paymentRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_payment_request_index');
        }

        return $this->render('admin_payment_request/edit.html.twig', [
            'payment_request' => $paymentRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_payment_request_delete", methods={"POST"})
     */
    public function delete(Request $request, PaymentRequest $paymentRequest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentRequest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paymentRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_payment_request_index');
    }
}
