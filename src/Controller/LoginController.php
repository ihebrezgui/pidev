<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */
    public function login(Request $request, UserProviderInterface $userProvider, UserPasswordEncoderInterface $passwordEncoder)
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Find the user by email
            $user = $userProvider->loadUserByUsername($email);

            if (!$user) {
                $error = 'Invalid email or password.';
            } else {
                // Check if the password is correct
                if (!$passwordEncoder->isPasswordValid($user, $password)) {
                    $error = 'Invalid email or password.';
                } else {
                    // Authentication successful
                    // Redirect to a protected page
                    // For example: return $this->redirectToRoute('app_homepage');
                }
            }
        }

        return $this->render('login.html.twig', [
            'error' => $error,
            'last_username' => $request->request->get('email'),
        ]);
    }
}
