<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Titre'])
            ->add('description', null, ['label' => 'Description'])
            ->add('author', null, ['label' => 'Auteur'])
            ->add('category', null, ['label' => 'Catégorie'])
            ->add('publishingHouse', null, ['label' => 'Maison d\'édition'])
            ->add('isbn', null, ['label' => 'ISBN'])
            ->add('numberOfPages', null, ['label' => 'Nombre de pages'])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => 'Ajouter une image',
                'label_attr' => [
                     'data-browse' => 'Parcourir'
                ]
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
