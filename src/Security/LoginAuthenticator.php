<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use App\Repository\UserRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private $userRepository;
    private $router;

    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'user_login' && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // todo: call dummy hash if no user found
        return $this->userRepository->findOneBy(['username' => $credentials['username']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo: check password here
        dd($user);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // todo: redirect to user page here
        return new RedirectResponse($this->router->generate("index"));
    }

    protected function getLoginUrl()
    {
        // todo: redirect to login page here
        // return $this->router->generate('app_login');
    }
}
