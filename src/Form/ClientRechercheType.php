<?php


namespace App\Form;

use App\Entity\ClientRecherche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ClientRechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'required' => false,
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
            ])
            ->add('mail', TextType::class, [
                'label' => 'Mail',
                'required' => false,
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Telephone',
                'required' => false,
            ])
            ->add('date', DateType::class, [
                'label' => 'Date d\'entrée',
                'required' => false,
                'invalid_message' => 'Date attendue'
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientRecherche::class,
        ]);
    }
}