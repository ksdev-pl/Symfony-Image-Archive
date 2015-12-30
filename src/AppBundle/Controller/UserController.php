<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AppController
{
    /**
     * @Route("/users")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('user/index.html.twig', compact('users'));
    }

    /**
     * @Route("/users/create")
     * @Method("GET")
     *
     * @return Response
     */
    public function createAction()
    {
        $isCreateAction = true;

        return $this->render('user/create.html.twig', compact('isCreateAction'));
    }

    /**
     * @Route("/users")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function storeAction(Request $request)
    {
        $this->parseJsonRequest($request);

        $user = new User();
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));

        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/users/{id}/edit", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param User $user
     *
     * @return Response
     */
    public function editAction(User $user)
    {
        return $this->render('user/edit.html.twig', compact('user'));
    }

    /**
     * @Route("/users/{id}", requirements={"id": "\d+"})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param User $user
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, User $user)
    {
        $this->parseJsonRequest($request);

        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));

        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/users/{id}", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param User $user
     *
     * @return Response
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_user_index');
    }
}
