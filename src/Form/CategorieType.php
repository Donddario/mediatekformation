<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire créer ou modifier une Categorie.
 */
class CategorieType extends AbstractType
{
    /**
     * Génère le formulaire pour l'entité Categorie.
     *
     * @param FormBuilderInterface
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Nom de la catégorie
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ]);
    }

    /**
     * Configure les options par défaut du formulaire.
     *
     * @param OptionsResolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
