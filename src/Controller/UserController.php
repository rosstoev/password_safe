<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\User;
use App\Entity\WebsiteData;
use App\Form\WebsiteDataFormType;
use App\Repository\UserRepository;
use App\Repository\WebsiteDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(UserRepository $userRepo, ParameterBagInterface $parameterBag)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->isGoogleAuthenticate() == false) {
            return $this->redirectToRoute('login_level_two');
        }
        $userData = $userRepo->findOneBy(['id' => $user]);
        $result = [];
        if ($userData->getWebsites()->count() > 0) {
            foreach ($userData->getWebsites() as $website) {
                $plainPassword = $website->decryptPassword($parameterBag);
                $data = new \stdClass();
                $data->id = $website->getId();
                $data->url = $website->getUrl();
                $data->password = $plainPassword;
                $result[] = $data;
            }
        }

        return $this->render("pages/user/dashboard.html.twig", [
            'websites' => $result,
            'parameterBag' => $parameterBag
        ]);

    }

    /**
     * @Route ("/website-data/manage/{websiteData}", name="website-data_manage", defaults={"websiteData": null})
     */
    public function manageWebsite(
        Request $request,
        WebsiteDataRepository $websiteDataRepository,
        UserRepository $userRepository,
        ?WebsiteData $websiteData,
        ParameterBagInterface $parameterBag
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->isGoogleAuthenticate() == false) {
            return $this->redirectToRoute('login_level_two');
        }
        if (!empty($websiteData)) {
            $plainPassword = $websiteData->decryptPassword($parameterBag);
            $websiteData->setPassword($plainPassword);
            $edit = true;
        }
        $form = $this->createForm(WebsiteDataFormType::class, $websiteData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var WebsiteData $formData */
            $formData = $form->getData();
            $userData = $userRepository->findOneBy(['id' => $user]);
            try {
                $websiteDataRepository->save($formData, $userData);
                return $this->redirectToRoute('user_dashboard');
            } catch (\Exception $ex) {
                $error = 'Възникна грешка, опитайте отново!';
            }
        }

        return $this->render("pages/user/manage.website.html.twig", [
            "form" => $form->createView(),
            "error" => $error ?? null,
            "edit" => $edit ?? false
        ]);
    }

    /**
     * @Route ("website-data/remove/{websiteData}", name="website-data_remove")
     */
    public function delete(WebsiteData $websiteData, WebsiteDataRepository $websiteRepo)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->isGoogleAuthenticate() == false) {
            return $this->redirectToRoute('login_level_two');
        }

        try {
            $websiteRepo->remove($websiteData);
            return $this->redirectToRoute('user_dashboard');
        } catch (\Exception $ex) {
            $error = 'Възникна грешка, опитайте отново!';
        }
        return new Response($error ?? null);
    }
}