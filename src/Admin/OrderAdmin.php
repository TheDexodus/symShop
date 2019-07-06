<?php

namespace App\Admin;

use App\Entity\Order;
use App\Entity\User;
use App\Form\PositionType;
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
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
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
            ->add('positions', CollectionType::class, [
                'entry_type' => PositionType::class,
                'allow_add' => true,
                'required'   => true,
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

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
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

    /**
     * @param $object
     * @return string
     */
    public function toString($object): string
    {
        return 'Order';
    }
}
