<?php
namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class ChangePasswordForm extends Form
{

    public function initialize()
    {
        // Password
        $password = new Password('password', [
            'placeholder' => 'Password',
            'class' => 'form-control'
        ]);

        $password->addValidators([
            new PresenceOf([
                'message' => 'Password is required'
            ]),
            new StringLength([
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters'
            ]),
            new Confirmation([
                'message' => 'Password doesn\'t match confirmation',
                'with' => 'confirmPassword'
            ])
        ]);

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword', [
            'placeholder' => 'Confirme a password',
            'class' => 'form-control'
        ]);

        $confirmPassword->addValidators([
            new PresenceOf([
                'message' => 'The confirmation password is required'
            ])
        ]);

        $this->add($confirmPassword);
    }
}
