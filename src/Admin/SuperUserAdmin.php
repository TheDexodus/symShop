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
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper): void
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

            $admin->setUsername($admin->getEmail());
            $admin->setEnabled(true);

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

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
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

    /**
     * @param $object
     * @return string
     */
    public function toString($object): string
    {
        return $object instanceof Admin ? $object->getEmail() : 'Super User';
    }
}
