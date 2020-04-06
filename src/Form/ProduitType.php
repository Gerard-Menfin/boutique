<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints; 

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, [ "label" => "Référence" ])
            ->add('categorie')
            ->add('titre', TextType::class, [ "label" => "Titre du produit", "constraints" => [ new Constraints\Length([ "min" => 3, "max" => 20,
                                                                                                                         "minMessage" => "Le titre doit avoir au moins 3 caractères",
                                                                                                                         "maxMessage" => "Le titre ne peut avoir plus de 20 caractères"
                                                                                                                        ]) ]  ])
            ->add('description')
            ->add('couleur')
            ->add('public')
            ->add('photo', FileType::class, [ "mapped" => false, "help" => "* requis" ])
            ->add('prix', MoneyType::class, ["constraints" => [ 
                                                                new Constraints\Range( [
                                                                    'min' => 10,
                                                                    'max' => 1500,
                                                                    'minMessage' => 'Le prix doit être de 10€ au minimum',
                                                                    'maxMessage' => 'Le prix doit être de  {{ limit }}€ au maximum',
                                                                ]) 
                                                            
                                                              ]])
            ->add('stock', NumberType::class, [ "help" => "Le stock doit être supérieur à 0", "constraints" => [ new Constraints\Positive( [ "message" => "Vous devez taper un nombre positif" ] ) ] ])
            ->add('taille')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
