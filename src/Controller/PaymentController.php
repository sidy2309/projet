<?php

namespace App\Controller;

use App\Entity\PaymentRequest;
use App\Entity\Order;
use App\Entity\OrderedQuantity;
use App\Repository\GameRepository;
use App\Repository\PaymentRequestRepository;
use App\Service\CartService;
use App\Service\PaymentService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment_index")
     */
    public function index(PaymentService $paymentService): Response
    {
        $sessionId = $paymentService->create();

        $paymentRequest = new PaymentRequest();
        $paymentRequest -> setCreatedAt(new DateTime());
        $paymentRequest -> setStripeSessionId($sessionId);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager -> persist($paymentRequest);
        $entityManager ->flush();

        return $this->render('payment/index.html.twig', [
            'sessionId' => $sessionId
        ]);
    }

    /**
     * @Route("/payment/success/{stripeSessionId}", name="payment_success")
     */
    public function success(string $stripeSessionId, PaymentRequestRepository $paymentRequestRepository, CartService $cartService, GameRepository $gameRepository ): Response
    {
        $paymentRequest = $paymentRequestRepository->findOneBy([
            'stripeSessionId' =>$stripeSessionId

        ]);
        if (!$paymentRequest)
        {
            return $this->redirectToRoute('cart_index');
        }

        $paymentRequest->setValidated(true);
        $paymentRequest->setPaidAt(new DateTime());

        $entityManager =$this->getDoctrine()->getManager();

        $order = new Order();
        $order->setCreateAt(new DateTime());
        $order->setSendAt(new DateTime());
        $order->setPaymentRequest($paymentRequest);
        $order->setUser($this->getUser());
        $order->setReference(strval(rand(1000000,99999999)));
        $entityManager->persist($order);

        $cart = $cartService->get();
        foreach($cart['elements'] as $gameId => $element )
        {
            $game = $gameRepository->find($gameId);
            $orderedQuantity = new OrderedQuantity();
            $orderedQuantity->setQuantity($element['quantity']);
            $orderedQuantity->setGame($game);
            $orderedQuantity->setFromOrder($order);
        }
        

        $entityManager->flush();

        $cartService->clear();


        return $this->render('payment/success.html.twig');
    }

    /**
     * @Route("/payment/failure/{stripeSessionId}", name="payment_failure")
     */
    public function failure(string $stripeSessionId, PaymentRequestRepository $paymentRequestRepository): Response
    {
        $paymentRequest = $paymentRequestRepository->findOneBy([
            'stripeSessionId' =>$stripeSessionId

        ]);
        if (!$paymentRequest)
        {
            return $this->redirectToRoute('cart_index');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($paymentRequest);
        $entityManager->flush();

        return $this->render('payment/failure.html.twig');
    }
}
