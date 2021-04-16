<?php

namespace App\Service;

use App\Entity\Game;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService 
{

    public function __construct(SessionInterface $sessionInterface)
    {

       $this->sessionInterface = $sessionInterface; 

    }
    public function get()
    {
        return $this->sessionInterface->get('cart', [  'elements'=>[],
        'total'=> 0.0

        ]);
    }

    public function add(Game $game)
    {
          $cart= $this ->get();
          $gameId = $game->getId();

          if (!isset($cart['elements'][$gameId]))
          {
             $cart['elements'][$gameId] = [
                 'game' =>$game,
                 'quantity' => 0

             ];
          }

          $cart['total'] = $cart['total'] + $game->getPrice();
          $cart['elements'][$gameId]['quantity'] = $cart['elements'][$gameId]['quantity'] + 1 ;

          $this->sessionInterface->set('cart', $cart);
    }

    public function remove( Game $game)
     {
         $cart = $this->get();
         $gameId = $game->getId();
         if (!isset($cart['elements'][$gameId]))
         {
            return;
         }
         $cart['total'] = $cart['total'] -$game->getPrice();
         $cart['elements'][$gameId]['quantity'] = $cart['elements'][$gameId]['quantity'] - 1 ;

         if ($cart['elements'][$gameId]['quantity'] <=0)
         {
             unset($cart['elements'][$gameId]);
         }

         $this->sessionInterface->set('cart', $cart);
     }

     public function clear()
     {
         $this-> sessionInterface ->remove('cart');
     }

}