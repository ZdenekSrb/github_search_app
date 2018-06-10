<?php

namespace AppBundle\Controller;

use AppBundle\Form\DeleteLogForm;
use AppBundle\Form\FindUserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class GitController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(FindUserForm::class);

        //handle POST request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user_name = $form->getData();
            $user_name->setIp();
            $user_name->setCreated();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user_name);
            $em->flush();

            $profile = $this->get('app.gitapi')->getProfile($user_name->getUserName());

            if (!is_array($profile))
            {
                $this->addFlash('error', $profile.' uživatel nenalezen');
                return $this->redirectToRoute('homepage');
            }
            else
            {
                return $this->redirectToRoute('repository_user', ['username' => $profile['login']]);
            }
        }

        return $this->render('homepage/index.html.twig', ['searchForm' => $form->createView()]);
    }

    /**
     * @Route("/repository/{username}", name="repository_user")
     */
    public function repositoryAction($username)
    {
        $repository = $this->get('app.gitapi')->getRepos($username);
        return $this->render('repository/repository.html.twig', $repository);
    }

    /**
     * @Route("/log", name="search_log")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT l.id, l.UserName, l.Created, l.Ip FROM AppBundle:UserName l ORDER BY l.Created DESC');
        $logs = $query->getResult();

        return $this->render('log/log.html.twig', ['logs' => $logs]);
    }

    /**
     * @Route("/log/delete", name="delete_log")
     */
    public function deleteAction(Request $request)
    {
        $form = $this->createForm(DeleteLogForm::class);

        //handle POST request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $hour_number = $form->getData();
            $delete_date = date_sub(new \DateTime("now"), new \DateInterval('P0YT'.$hour_number->getHourNumber().'H'));
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery('DELETE FROM AppBundle:UserName l WHERE l.Created < :delete_date')->setParameter('delete_date', $delete_date);
            $number_of_deleted_rows = $query->getResult();
            $this->addFlash('success', 'Počet odstraněných záznamů: '.$number_of_deleted_rows);
            return $this->redirectToRoute('homepage');

        }
        return $this->render('log/delete.html.twig', ['deleteForm' => $form->createView()]);
    }
}
