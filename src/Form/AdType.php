<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{
/**
 * Permet d'avoir la configuration de base d'un champ
 *
 * @param String $label
 * @param String $placeholder
 * @param array $options
 * @return array
 */
    private function getConfiguration($label, $placeholder,$options = []){
        return array_merge([
            'label'=>$label,
            'attr'=> [
                'placeholder'=> $placeholder
            ]
            ], $options);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,$this->getConfiguration("titre", "Tapez un super titre"))
            ->add('slug',
             TextType::class,
             $this->getConfiguration("Adresse web", "Tapez une adresse web", [
                'required' => false
            ]))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale"))
            ->add('content', TextareaType::class, $this->getConfiguration("Contenu", "Tapez un contenu"))
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL Image", "Mettez une image principale"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambres", "Entrez le nombre de chambres"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit", "Indiquer le prix pour une nuit"))
            ->add('images',
                CollectionType::class, [
                    'entry_type' => ImageType::class,
                    'allow_add' => true
                ])
            ->add('save', SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
