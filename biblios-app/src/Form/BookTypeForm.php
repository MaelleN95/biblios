<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Editor;
use App\Enum\BookStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BookTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du livre',
                'required' => true,
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'required' => true,
            ])
            ->add('cover', TextType::class, [
                'label' => 'Couverture',
                'required' => false,
            ])  
            ->add('editedAt', DateType::class, [
                'label' => 'Date de publication',
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('plot', TextType::class, [
                'label' => 'Résumé',
                'required' => false,
            ])
            ->add('pageNumber', IntegerType::class, [
                'label' => 'Nombre de pages',
                'required' => true,
            ])
            ->add('status', EnumType::class, [
                'class' => BookStatus::class,
                'label' => 'Statut',
                // Use a callback to get the label for each enum value
                'choice_label' => function(BookStatus $status) {
                    return $status->getLabel();
                },
                'required' => true,
            ])
            ->add('editor', EntityType::class, [
                'class' => Editor::class,
                'choice_label' => 'id',
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'id',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
