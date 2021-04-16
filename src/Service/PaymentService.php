<?php

namespace App\Service;

use Stripe\StripeClient;



class PaymentService 
{
     public function __construct(CartService $cartService)
     {
        $this->cartService = $cartService;
        $this->stripe = new StripeClient('sk_test_51IgofLKBFqSTmBBJ8U7znSpErcrnFTwtlEggXtrFMIRjsnrEYlBCbnNg46RiV5jJMRaezV9vNnsQvqdoJnoXTCbv00YCF3WqHH');

     }

     public function create(): string
     {
        $cart = $this->cartService->get();
        $items =[];
        foreach ($cart['elements'] as $gameId => $element )
        {
            $items[] = [
                'amount' => $element['game']->getPrice() *100,
                'quantity' => $element['quantity'],
                'currency' => 'eur',
                'name' => $element['game'] ->getName()

            ];
        }
        $protocol = $_SERVER['HTTPS'] ? 'https' : 'http';
        $host = $_SERVER['SERVER_NAME'];
        $successUrl = $protocol . '://' . $host . '/payment/success/{CHECKOUT_SESSION_ID}';
        $failureUrl = $protocol . '://' . $host . '/payment/failure/{CHECKOUT_SESSION_ID}';
   
        
        $session = $this->stripe->checkout->sessions->create([
            'success_url' => $successUrl,
            'cancel_url'  => $failureUrl,
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $items

        ]);

        return $session->id;
     }
}