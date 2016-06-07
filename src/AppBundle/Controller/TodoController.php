<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Todo;

class TodoController extends Controller {
    
    /**
     * @REST\Get("/test")
     */
    public function testAction() {
        return new JsonResponse('hello, world');
    }
    
    /**
     * @REST\Get("/api/todos")
     */
    public function indexAction() {
        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findAll();
            
        $res = [];
        foreach ($todos as $todo) {
            $res[] = $todo->toArray();
        }
        
        return new JsonResponse($res);
    }
    
    /**
     * @REST\Get("/api/todos/{id}")
     */
    public function viewAction($id) {
        $todoRepository = $this->getDoctrine()->getRepository('AppBundle:Todo');
        $todo = $todoRepository->find($id);
        
        if ($todo === null) {
            throw new NotFoundHttpException('Could not find item');
        }
        
        return new JsonResponse($todo->toArray());
    }
    
    /**
     * @REST\Post("/api/todos")
     */
    public function addAction(Request $request) {
        $params = [];
        $raw = $request->getContent();
        if (!empty($raw))
        {
            $raw = json_decode($raw, true); // 2nd param to get as array
        }
        
        $todo = new Todo();
        $todo->setName($raw['name']);
        $todo->setCompleted($raw['completed']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($todo);
        $em->flush();
        
        return new JsonResponse($todo->toArray());
    }
    
    /**
     * @REST\Put("/api/todos/{id}")
     */
    public function editAction(Request $request, $id) {
        $todoRepository = $this->getDoctrine()->getRepository('AppBundle:Todo');
        $todo = $todoRepository->find($id);
        
        if ($todo === null) {
            throw new NotFoundHttpException('Could not find item');
        }
        
        $params = [];
        $raw = $request->getContent();
        if (!empty($raw))
        {
            $raw = json_decode($raw, true); // 2nd param to get as array
        }
        
        $todo->setName($raw['name']);
        $todo->setCompleted($raw['completed']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($todo);
        $em->flush();
        
        return new JsonResponse($todo->toArray());
    }

    /**
     * @REST\Delete("/api/todos/{id}")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getReference('AppBundle:Todo', $id);
        $em->remove($todo);
        $em->flush();
        
        return new JsonResponse(true);
    }
}