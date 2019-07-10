<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();  

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new" , methods="GET|POST")
     */
    public function new(Request $request):Response
    {
       $user = new User();
       $form = $this->createForm(UsersType::class , $user);
       $form->handleRequest($request);
       if ($request->get("email")!="") {
        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));
        $user->setRoles($request->get('roles'));
        $user->setStatus($request->get('status'));
        $user->setPassword($request->get('password'));
           $em = $this->getDoctrine()->getManager();
           $em->persist($user);
           $em->flush();
           
           return $this->redirectToRoute('admin_user');
       }
        return $this->render('admin/user/create_form.html.twig', [
            'form' => $form ->createView(),
           
        ]);
    }
    /**
     * @Route("/admin/user/edit/{id}", name="admin_user_edit" , methods="GET|POST")
     */
    public function edit(Request $request, User $users):Response
    {
        $form = $this->createForm(UsersType::class , $users);
        $form->handleRequest($request);

        if ($form ->isSubmitted()){
              $this->getDoctrine()->getManager()->flush();
              return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/user/edit_form.html.twig', [
            'user'=> $users,
            'form' => $form ->createView(),
           
        ]);
    }
     /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     */
    public function delete(User $users)
    {
       $em = $this->getDoctrine()->getManager();
       $em->remove($users);
       $em->flush();
       return $this->redirectToRoute('admin_user');

    }
}
