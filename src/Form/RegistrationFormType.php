<?php

namespace App\Form;

use App\Entity\User;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('codePostal' ,  \Symfony\Component\Form\Extension\Core\Type\IntegerType::class , [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre code  postal',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4,
                    ]),
                ],
            ])
            ->add('gouvernorat', ChoiceType::class, [
                'choices'  => [
                    'Selectionner ' => null,
                    'Bizerte' => 'Bizerte',
                    'Tunis' => 'Tunis',
                    'Beja' => 'Beja',
                    'Jendouba' => 'Jendouba',
                    'Ariana' => 'Ariana',
                    'Manouba' => 'Manouba',
                    'Ben Arous' => 'Ben Arous',
                    'Zaghuoan' => 'Zaghouan',
                    'Siliana' => 'Siliana',
                    'Kairouan' => 'Kairouan',
                    'Le Kef' => 'Le Kef',
                    'Nabeul' => 'Nabeul',
                    'Kasserine' => 'Kasserine',
                    'Sidi Bou Zid' => 'Sidi Bou Zid',
                    'Sousse' => 'Sousse',
                    'Monastir' => 'Monastir',
                    'Mahdia' => 'Mahdia',
                    'Sfax' => 'Sfax',
                    'Gafsa' => 'Gafsa',
                    'Touzeur' => 'Touzeur',
                    'Kebili' => 'Kebili',
                    'Gabes' => 'Gabes',
                    'Medenine' => 'Medenine',
                    'Tataouine' => 'Tataouine',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez selectionner gouvernorat',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'UserCaptcha'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
