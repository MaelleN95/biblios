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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class BookTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'book.form.title',
                'required' => true,
            ])
            ->add('isbn', TextType::class, [
                'label' => 'book.form.isbn',
                'required' => true,
            ])
            ->add('cover', UrlType::class, [
                'label' => 'book.form.cover',
                'required' => false,
            ])  
            ->add('editedAt', DateType::class, [
                'label' => 'book.form.edited_at',
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('plot', TextareaType::class, [
                'label' => 'book.form.plot',
                'required' => false,
            ])
            ->add('pageNumber', IntegerType::class, [
                'label' => 'book.form.page_number',
                'required' => true,
            ])
            ->add('status', EnumType::class, [
                'class' => BookStatus::class,
                'label' => 'book.form.status',
                // Use a callback to get the label for each enum value
                'choice_label' => function(BookStatus $status) {
                    return $status->getLabel();
                },
                'required' => true,
            ])
            ->add('editor', EntityType::class, [
                'class' => Editor::class,
                'choice_label' => 'id',
                'label' => 'book.form.editor',  
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'label' => 'book.form.authors',
                'choice_label' => 'id',
                'multiple' => true,
                'by_reference' => false,
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
