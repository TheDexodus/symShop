<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Waiting' => Order::STATUS_WAITING,
                    'Canceled' => Order::STATUS_CANCELED,
                    'Success' => Order::STATUS_SUCCESS,
                ]
            ])
            ->add('created_date', DateType::class)
            ->add('user_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'disabled' => !empty($data),

            ])
            ->add('products', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Product::class,
                    'choice_label' => 'name',
                ],
                'allow_add' => true,
                'disabled' => !empty($data),
            ])
            ->add('save', SubmitType::class, ['label' => 'Save changes']);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Order $order */
            $order = $event->getData();

            $order->setLaterPrice($order->calculateLaterPrice());
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
