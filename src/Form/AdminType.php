<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['required' => false])
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
            ])
            ->add('roles', CollectionType::class, [
                'entry_type' => TextType::class,
                'required' => false,
                'allow_add' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Save changes']);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Admin $admin */
            $admin = $event->getData();

            if (!empty($admin->getPlainPassword())) {
                $admin->setPassword(password_hash($admin->getPlainPassword(), PASSWORD_BCRYPT));
            }

//            if (empty($data['roles'])) {
//                $data['roles'] = [];
//            }

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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
