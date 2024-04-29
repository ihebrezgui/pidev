<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\FormError;
use App\Form\ChangePasswordType;
use App\Form\UserAdminType;
use App\Form\UserWithoutPasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController

    {
        #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
        public function index(UtilisateurRepository $utilisateurRepository, Request $request)
    {
        $searchQuery = $request->query->get('search');

        if ($searchQuery !== null) {
            $utilisateurs = $utilisateurRepository->findBySearchQuery($searchQuery);
        } else {
            // If no search query is provided, fetch all users
            $utilisateurs = $utilisateurRepository->findAll();
        }

        return $this->render('back/utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('back/utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(UtilisateurType::class, $utilisateur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    $this->disablePasswordField($form);

    return $this->render('back/utilisateur/edit.html.twig', [
        'utilisateur' => $utilisateur,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
    private function disablePasswordField(FormInterface $form): void
{
    $form->remove('password');
}
public function usersByAgeChart(UtilisateurRepository $utilisateurRepository)
{
    $utilisateurs = $utilisateurRepository->findAll(); 

    // Define age groups
    $ageGroups = [
        '11-20' => 0,
        '21-30' => 0,
        '31-40' => 0,
        '41-50' => 0,
        '51+' => 0,
    ];

    foreach ($utilisateurs as $user) {
        $age = $this->calculateAge($user->getDateNais());
        if ($age <= 20) {
            $ageGroups['11-20']++;
        } elseif ($age <= 30) {
            $ageGroups['21-30']++;
        } elseif ($age <= 40) {
            $ageGroups['31-40']++;
        } elseif ($age <= 50) {
            $ageGroups['41-50']++;
        } else {
            $ageGroups['51+']++;
        }
    }

    $chartData = [
        'labels' => array_keys($ageGroups),
        'data' => array_values($ageGroups),
    ];

    return $this->render('back/utilisateur/age_stat.html.twig', [
        'chartData' => $chartData,
    ]);
}
private function calculateAge(\DateTimeInterface $dateOfBirth)
{
    $now = new \DateTime();
    $interval = $now->diff($dateOfBirth);
    return $interval->y;
}
#[Route('/register', name: 'app_register')]
public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new Utilisateur();
    $form = $this->createForm(UtilisateurType::class, $user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $existingUser = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $user->getEmail()]);

        if ($existingUser) {
            $form->get('email')->addError(new FormError('This email is already registered.'));
            return $this->render('front/utilisateur/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_myprofile');
    }

    return $this->render('front/utilisateur/register.html.twig', [
        'registrationForm' => $form->createView(),
        'recaptcha_site_key' => '6LftLccpAAAAAFevt3JkRUJQFS1cbLe54IVgprDD',
    ]);
    
}
#[Route('/MonProfile', name: 'app_myprofile')]
public function Myprofile(): Response
{

    return $this->render('front/utilisateur/profile.html.twig', [
        'user' => $this->getUser(),
    ]);
}
#[Route('/modifier-profil', name: 'app_edit_profile')]
public function editProfile(Request $request, UtilisateurRepository $userRepository, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    $form = $this->createForm(UserWithoutPasswordType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Update the user entity with the form data
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Profile updated successfully.');
        // Redirect to some route after successful update
        return $this->redirectToRoute('app_myprofile');
    }

    return $this->render('front/utilisateur/edit_profile.html.twig', [
        'profileForm' => $form->createView(),
    ]);
}


#[Route('/EditProfilePassword', name: 'app_edit_profile_password', methods: ['GET', 'POST'])]
public function EditProfilePassword(Request $request,UserPasswordHasherInterface $userPasswordHasher,UtilisateurRepository $userRepository): Response
{

    $user = $this->getUser();
    if($request->get("newpass")=="" && $request->get("confirmpass")=="")
    {
        $this->addFlash(
            'info-warning',
            'Password empty.'
        );
        return $this->render('index/profile.html.twig', [
            'user' => $user,
        ]);
    }
    else
    {
        if($request->get("newpass")==$request->get("confirmpass"))
        {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $request->get("newpass")
                )
            );

            $userRepository->updateUserPassword($user, true);
            $this->addFlash(
                'info-success',
                'Password changed with success.'
            );

            return $this->render('index/profile.html.twig', [
                'user' => $user,
            ]);
        }
        else
        {
            $this->addFlash(
                'info-warning',
                'Password dont match.'
            );
        }

        return $this->render('index/profile.html.twig', [
            'user' => $user,
        ]);
    }

}


#[Route('/reset-password/submitted', name: 'app_reset_password_submited')]
public function resetPasswordSubmitted(Request $request, EntityManagerInterface $entityManager , MailerInterface $mailer)
{

    $toemail = $request->get('email');
    $user = $entityManager->getRepository(Utilisateur::class)->getUserByEmail($toemail);

    if ($user) {
        $restToken= $this->generateResetCode();
        $user->setResttoken($restToken);
        $entityManager->flush();

        $html = '
                <html>
                    <body>
                        <p>Bonjour utilisateur,</p>
                        <p>Quelqu\'un a demandé un lien pour changer votre mot de passe. Vous pouvez le faire via le lien ci-dessous.</p>
                        <p><a href="http://127.0.0.1:8000/utilisateur/verify-reset-code/'.$restToken.'">Changer mon mot de passe</a></p>
                        <p>Si vous n\'avez pas effectué cette demande, veuillez ignorer cet e-mail.</p>
                        <p>Votre mot de passe ne sera pas modifié tant que vous n\'aurez pas accédé au lien ci-dessus et créé un nouveau.</p>
                    </body>
                </html>
            ';
        $email = (new Email())
            ->from('rihabtlili2020@gmail.com')
            ->to($toemail)
            ->subject('Reset Password')
            ->html($html);
        $mailer->send($email);

        return $this->redirectToRoute('app_login');
    }
    else
    {
        $this->addFlash(
            'error',
            'Email does not exist.'
        );

        return $this->redirectToRoute('reset_password');
    }

}
#[Route('/reset-password', name: 'reset_password')]
public function resetPassword(Request $request, EntityManagerInterface $entityManager): Response
{
    return $this->render('front/utilisateur/reset_password.html.twig', []);
}


#[Route('/verify-reset-code/{restToken}', name: 'verify_reset_code')]
public function verifyResetCode(Request $request, $restToken, EntityManagerInterface $entityManager ,UserPasswordHasherInterface $userPasswordHasher)
{
    $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['resttoken' => $restToken]);
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $form = $this->createForm(ChangePasswordType::class,$user);
    $form->handleRequest($request);
    $data = $form->getData();


    if ($form->isSubmitted() && $form->isValid()) {
        $hashedPassword = $userPasswordHasher->hashPassword($user, $data->getPassword());
        $user->setPassword($hashedPassword);
        $user->setResttoken(null);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Mot de passe changer avec succès.'
        );

        // Redirect or render a success message
        return $this->redirectToRoute('app_login');
    }

    return $this->render('front/utilisateur/verify_reset_code.html.twig', [
        'form' => $form->createView(),
    ]);
}

private function generateResetCode()
{
    return uniqid();
}
#[Route('/resetPassword/profil', name: 'reset_password_profil')]
    public function resetPasswordProfile(Request $request, EntityManagerInterface $entityManager ,UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class,$user);
        $form->handleRequest($request);
        $data = $form->getData();


        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword($user, $data->getPassword());
            $user->setPassword($hashedPassword);
            $user->setResttoken(null);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Mot de passe changer avec succéss.'
            );

            return $this->redirectToRoute('app_myprofile');
        }

        return $this->render('front/utilisateur/verify_reset_code.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/user-role-statistics', name: 'user_role_statistics')]
    public function userRoleStatistics(UtilisateurRepository $utilisateurRepository): Response
    {
        $usersByRole = $utilisateurRepository->getUsersByRole();
    
        // Prepare data for Google Chart
        $data = [];
        $data[] = ['Role', 'Number of Users'];
        foreach ($usersByRole as $userData) {
            $data[] = [$userData['role'], $userData['userCount']];
        }
    
        return $this->render('back/utilisateur/user_role_statistics.html.twig', [
            'chartData' => json_encode($data),
        ]);
    }
}
