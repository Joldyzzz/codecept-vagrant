<?php
class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Login', 'h1');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginById(\FunctionalTester $I)
    {
        $I->amLoggedInAs(425046);
        $I->amOnPage('/');
        $I->see('Logout (joldyzzz@mail.ru)');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginByIdD(\FunctionalTester $I)
    {
        $I->amLoggedInAs(425202);
        $I->amOnPage('/');
        $I->see('Logout (dimkreativ@gmail.com)');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginByInstance(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\Users::findByEmail('joldyzzz@mail.ru'));
        $I->amOnPage('/');
        $I->see('Logout (joldyzzz@mail.ru)');
    }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[email]' => 'joldyzzz@mail.ru',
            'LoginForm[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password.');
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[email]' => 'joldyzzz@mail.ru',
            'LoginForm[password]' => 'qwerty',
        ]);
        $I->see('Logout (joldyzzz@mail.ru)');
        $I->dontSeeElement('form#login-form');
    }
}