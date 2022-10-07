<?php

namespace App\Form;

use App\Entity\Produits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre:',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le nom du produit',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez la description du produit'
                ]
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Active:',
                'required' => true,
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix:',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le prix du produit',
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
                'label' => 'Image',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
