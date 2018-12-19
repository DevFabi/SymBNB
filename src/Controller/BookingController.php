<?php

namespace App\Controller;

use App\Entity\Ad;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\BookingType;
use App\Entity\Booking;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     */
    public function book(Ad $ad)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        return $this->render('booking/book.html.twig', [
            'ad' =>$ad,
            'form' => $form->createView()
        ]);
    }
}
