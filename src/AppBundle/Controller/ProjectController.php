<?php

namespace AppBundle\Controller;

use AppBundle\Service\Menu;
use AppBundle\Service\MenuItem;
use AppBundle\Service\Mudnames;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;

/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/", name="project")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('AppBundle:Project')->findAll();

        $menu = (new Menu([new MenuItem('project_new', 'Add project')]))->getItems();

        //die(var_dump($menu[0]->title));
        //die(var_dump($projects));

        return $this->render('project/index.html.twig', compact('projects', 'menu'));
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $slug = $this->get('slugify')->slugify($project->getName());
            $slug = $this->ubiquitySlug($slug);
            $project->setSlug($slug);
            $project->setStatus(1);
            //TODO: get real account ID
            $project->setAccountId(1);
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return $this->render('project/new.html.twig', array(
            'project' => $project,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Route("/{slug}", name="project_show")
     * @Method("GET")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->findBySlug($slug);

        if(!$project) throw $this->createNotFoundException("Project with name \"$slug\" not found");

        $delete_form = $this->createDeleteForm($project)->createView();

        $submenu = (new Menu([
            new MenuItem($this->generateUrl('project_edit', compact('slug')), 'Edit'),
            new MenuItem(['form'=>$delete_form], 'Delete')
        ]))->getItems();

        return $this->render('project/show.html.twig', compact('project', 'delete_form', 'submenu'));
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{slug}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->findBySlug($slug);
        $deleteForm = $this->createDeleteForm($project);
        $editForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_edit', array('id' => $project->getId()));
        }

        return $this->render('project/edit.html.twig', array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{slug}", name="project_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->findBySlug($slug);
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
        }

        return $this->redirectToRoute('project');
    }

    /**
     * Creates a form to delete a Project entity.
     *
     * @param Project $project The Project entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('slug' => $project->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param $slug
     * @param string $newSlug
     * @return mixed
     * @internal param $em
     */
    private function ubiquitySlug($slug, $newSlug = '')
    {
        if(!$newSlug) $newSlug = $slug;
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->findBySlug($slug);

        if ($project) {
            $newSlug = $slug. '-' .Mudnames::generate_name_from();
            return $this->ubiquitySlug($slug, $newSlug);
        }

        return $newSlug;
    }
}
