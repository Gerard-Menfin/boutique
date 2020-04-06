<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'first_options'  => array('label' => 'Password',
                    'help' => 'Doit contenir au moins 6 caractères',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir un mot de passe : ',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    ),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            // ->add('plainPassword', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'mapped' => false,
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez saisir un mot de passe : ',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            //     'label' => "Mot de passe",
            //     'help' => 'Doit contenir au moins 6 caractères'
            // ])
            ->add('pseudo', TextType::class, [ "label" => "Pseudo" ])
            ->add('civilite', ChoiceType::class, [ "label" => "Civilité", 
                    "choices" => [ "M." => "h", "Mme" => "f", "Autre" => "a" ] ])
            ->add('prenom', TextType::class, [ "label" => "Prénom" ])
            ->add('nom', TextType::class)
            ->add('adresse', TextareaType::class)
            ->add('code_postal', TextType::class, [ "label" => "Code Postal",
                "constraints" => [ new Length([
                                        "min" => 5, "max" => 5,
                                        "exactMessage" => "Le code postal doit comporter 5 chiffres exactement"
                                    ]),
                                   new Regex([ "pattern" => "/[0-9]{5}/", 
                                               "message" => "Le code postal doit comporter 5 chiffres exactement"
                                             ]) 
                                 ] ])
            ->add('ville')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez valider les C.G.U.',
                    ]),
                ],
                'label' => "J'accepte les Conditions Générales d'Utilisation"
            ])

            ->add('Enregistrer', SubmitType::class, [ "attr" => [ "class" => "btn btn-primary" ] ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
