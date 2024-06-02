<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\UserType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, DocumentManager $documentManager): Response
    {
        $form = $this
            ->createForm(UserType::class, $this->getUser())
            ->handleRequest($request)
        ;

        if (($formSubmitted = $form->isSubmitted()) && $form->isValid()) {
            $documentManager->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form,
            'formSubmitted' => $formSubmitted,
        ]);
    }
}
