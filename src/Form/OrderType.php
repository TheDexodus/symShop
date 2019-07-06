<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('positions', CollectionType::class, [
                'entry_type' => PositionType::class,
                'allow_add' => true,
                'required'   => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Create order']);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Order $order */
            $order = $event->getData();

            $order->setLaterPrice($order->calculateLaterPrice());
            $order->setStatus(Order::STATUS_WAITING);

            $date = new \DateTime();
            $date->setTimestamp(time());
            $order->setCreatedDate($date);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
