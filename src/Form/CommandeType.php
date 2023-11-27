<?php

// src/Form/CommandeType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresseLivraison', TextType::class, ['label' => 'Adresse de livraison'])
            ->add('adresseFacturation', TextType::class, ['label' => 'Adresse de facturation'])
            ->add('modePaiement', TextType::class, ['label' => 'Mode de paiement']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurez ici vos options de formulaire, si nécessaire
        ]);
    }
}

