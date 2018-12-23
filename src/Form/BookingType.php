<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getConfiguration("Date d'arrivée", "La date à laquelle vous comptez arriver"))
            ->add('endDate', TextType::class, $this->getConfiguration("Date de départ", "La date à laquelle vous quitter les lieux"))
            ->add('comment', TextareaType::class, $this->getConfiguration(false,"Si vous avez des précisions à ajouter, n'hésitez pas !", [ "required" => false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
