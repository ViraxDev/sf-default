<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\UserType;
use App\Service\FileManager\FileManagerInterface;
use App\Service\FileUploader;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/profile/upload', name: 'app_picture_update')]
    public function updatePicture(Request $request, DocumentManager $documentManager, FileManagerInterface $fileManager): Response
    {
        $user = $this->getUser();
        $setter = 'set'. ucfirst($request->get('property'));
        $getter = 'get'. ucfirst($request->get('property'));

        /** @var UploadedFile $file  */
        if (
            $request->isXmlHttpRequest()
            && ($file = $request->files->get('photo')) instanceof UploadedFile
            && method_exists($user, $setter)
        ) {
            $filename = $fileManager->upload($file);
            $oldFilename = $user->{$getter}();

            $user->{$setter}($filename);
            $documentManager->flush();

            if ($oldFilename) {
                $fileManager->removeFile($oldFilename);
            }

            return $this->index($request, $documentManager);
        }
        
        return new JsonResponse('ko', 500);
    }
}
