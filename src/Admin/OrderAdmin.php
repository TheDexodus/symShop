<?php

namespace App\Admin;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class OrderAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var Order $order */
        $order = $this->getSubject();
        $formMapper
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Waiting' => Order::STATUS_WAITING,
                    'Canceled' => Order::STATUS_CANCELED,
                    'Success' => Order::STATUS_SUCCESS,
                ]
            ])
            ->add('created_date', DateType::class)
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'disabled' => !empty($order->getId()),

            ])
            ->add('products', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Product::class,
                    'choice_label' => 'name',
                ],
                'allow_add' => true,
                'disabled' => !empty($order->getId()),
            ]);
        $formMapper->getFormBuilder()->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Order $order */
            $order = $event->getData();

            if (empty($order->getId())) {
                $order->setLaterPrice($order->calculateLaterPrice());
            }
        });
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('status')
            ->add('created_date', 'datetime')
            ->add('user.email')
            ->add('laterPrice')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }

    public function toString($object)
    {
        return 'Order';
    }
}
