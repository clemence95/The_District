<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; // Importez TextareaType
use Symfony\Component\Form\Extension\Core\Type\EmailType; // Importez EmailType
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Importez SubmitType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email; // Importez Email depuis le composant de validation

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'constraints' => [
                    new Email([
                        'message' => 'L\'adresse e-mail "{{ value }}" n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [ // Utilisez TextareaType::class pour le champ textarea
                'label' => 'Votre message',
                'required' => true // Rendez le champ de message obligatoire
            ])
            ->add('save', SubmitType::class, [ // Utilisez SubmitType::class pour le champ de soumission
                'label' => 'Envoyer le message'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
