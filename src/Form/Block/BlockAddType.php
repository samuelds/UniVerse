<?php

namespace App\Form\Block;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

use App\Entity\Block;

class BlockAddType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Content',
                'property_path' => 'content[text]',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ],
            ]);
        ;
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void
    {
        $resolver->setDefaults([
            'data_class' => Block::class,
        ]);
    }
}
