<?php
namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function login(Request $request, AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Retrieve any authentication error message
        $error = $authenticationUtils->getLastAuthenticationError();

        // Retrieve the last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Check if the form was submitted
        if ($request->isMethod('POST')) {
            // Retrieve email and password from the form
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');

            // Find the user by email
            $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

            // Check if a user was found and if the password is correct
            if ($user && $passwordEncoder->isPasswordValid($user, $password)) {
                // Check if the user has the role of an admin
                if ($user->getRole() === 'Admin') {
                    // Redirect authenticated admin users to baseBack.html
                    return $this->redirectToRoute('baseBack');
                }
            }
        }

        // Render your login form template with any necessary data
        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
