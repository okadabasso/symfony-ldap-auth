<?php

namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class GroupEditForm  extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cn', TextType::class, ["required" => false])
            ->add('ou', TextType::class, ["required" => false])
            ->add('save', SubmitType::class)
        ;
    }
}