<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
    
        $lastUsername = $authenticationUtils->getLastUsername();
    
        if ($error instanceof BadCredentialsException) {
            $errorMessage = 'Invalid email or password.';
        } else {
            $errorMessage = $error ? $error->getMessage() : null;
        }
    
        
    return $this->render('back/utilisateur/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $errorMessage,
    ]);
    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    public function onLogoutSuccess(Request $request): Response
    {
        return new RedirectResponse($this->generateUrl('app_login'));
    }
 
}
