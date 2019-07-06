<?php

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class UserAdmin extends AbstractAdmin
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
            /** @var User $user */
            $user = $event->getData();

            $user->setUsername($user->getEmail());
            $user->setEnabled(true);

            if (!empty($user->getPlainPassword())) {
                $user->setPassword(password_hash($user->getPlainPassword(), PASSWORD_BCRYPT));
            }

            $roles = [];
            foreach ($user->getRoles() as $role) {
                if (empty($role)) {
                    continue;
                }
                $roles[] = $role;
            }

            $user->setRoles($roles);
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
        return $object instanceof User ? $object->getEmail() : 'User';
    }
}
