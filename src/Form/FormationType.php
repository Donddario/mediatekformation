<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Titre
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
            ])
            // Description
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            // Date de publication
            ->add('publishedAt', DateType::class, [
                'label' => 'Date de Publication',
                'widget' => 'single_text',
                'constraints' => [
                    new LessThanOrEqual('today'),
                ],
            ])
            // Playlist
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name',
                'label' => 'Playlist',
                'required' => true,
                'placeholder' => 'Sélectionnez une playlist',
            ])
            // Catégories
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'label' => 'Catégories',
                'multiple' => true,
                'required' => false,
                'expanded' => false,
            ])
            // Video ID
            ->add('videoId', TextType::class, [
                'label' => 'ID de la Vidéo YouTube',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
