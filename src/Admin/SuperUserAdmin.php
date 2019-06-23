<?php

namespace App\Admin;

use App\Entity\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class SuperUserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('email', EmailType::class, ['required' => false])
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
            ])
            ->add('roles', CollectionType::class, [
                'entry_type' => TextType::class,
                'required' => false,
                'allow_add' => true,
            ]);

        $formMapper->getFormBuilder()->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Admin $admin */
            $admin = $event->getData();

            if (!empty($admin->getPlainPassword())) {
                $admin->setPassword(password_hash($admin->getPlainPassword(), PASSWORD_BCRYPT));
            }

            $roles = [];
            foreach ($admin->getRoles() as $role) {
                if (empty($role)) {
                    continue;
                }
                $roles[] = $role;
            }

            $admin->setRoles($roles);
        });
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email')
            ->add('username')
            ->add('last_login', 'datetime')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }

    public function toString($object)
    {
        return $object instanceof Admin
            ? $object->getEmail()
            : 'Super User';
    }
}
