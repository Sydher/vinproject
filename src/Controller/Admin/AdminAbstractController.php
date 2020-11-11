<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Form\ConfirmDeleteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class AdminAbstractController extends AbstractController {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    protected $entity;
    protected $templatePath;
    protected $menu;
    protected $formClassName;
    protected $indexRouteName;
    protected $addRouteName;
    protected $editRouteName;
    protected $deleteRouteName;

    /**
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param RequestStack $requestStack
     */
    public function __construct(EntityManagerInterface $em,
                                PaginatorInterface $paginator,
                                RequestStack $requestStack) {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }

    /**
     * Affiche la liste des éléments à administrer.
     * @param QueryBuilder|null $query requête pour lister les éléments
     * @param int $numberPerPage nombre d'éléments par page
     * @return Response
     */
    protected function abstractIndex(QueryBuilder $query = null,
                                     int $numberPerPage = 10): Response {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        // Si aucune requête n'est fournis
        // Les éléments sont triés par date de création
        $query = $query ?: $this->getRepository()
            ->createQueryBuilder('row')
            ->orderBy('row.createdAt', 'DESC');

        $rows = $this->paginator->paginate(
            $query->getQuery(),
            $request->query->getInt('page', 1),
            $numberPerPage
        );

        $indexPath = "admin/{$this->templatePath}/index.html.twig";
        return $this->render($indexPath, [
            'rows' => $rows,
            'menu' => $this->menu,
            'addRouteName' => $this->addRouteName,
            'editRouteName' => $this->editRouteName,
            'deleteRouteName' => $this->deleteRouteName
        ]);
    }

    /**
     * Traite la création d'un nouvel élément.
     * @param $entity l'entité à créer
     * @return Response
     */
    protected function abstractNew($entity): Response {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        $form = $this->createForm($this->formClassName, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->applyNew($entity);
            $this->em->persist($entity);
            $this->em->flush();
            $this->flashSuccess("Created");
            return $this->redirectToRoute($this->indexRouteName);
        }

        $newPath = "admin/{$this->templatePath}/new.html.twig";
        return $this->render($newPath, [
            'form' => $form->createView(),
            'menu' => $this->menu,
            'indexRouteName' => $this->indexRouteName
        ]);
    }

    /**
     * Traite la modification d'un élément.
     * @param $id l'identifiant de l'entité à modifier
     * @return Response
     */
    protected function abstractEdit(string $id): Response {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        $entity = $this->getRepository()->find($id);
        $form = $this->createForm($this->formClassName, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->applyEdit($entity);
            $this->em->persist($entity);
            $this->em->flush();
            $this->flashSuccess("Updated");
            $this->em->refresh($entity);
            return $this->redirectToRoute($this->indexRouteName);
        }

        $newPath = "admin/{$this->templatePath}/edit.html.twig";
        return $this->render($newPath, [
            'form' => $form->createView(),
            'menu' => $this->menu,
            'entity' => $entity,
            'indexRouteName' => $this->indexRouteName
        ]);
    }

    /**
     * Traite la suppression d'un élément.
     * @param $id l'identifiant de l'entité à supprimer
     * @return Response
     */
    protected function abstractDelete(string $id): Response {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        $entity = $this->getRepository()->find($id);
        $form = $this->createForm(ConfirmDeleteFormType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($entity);
            $this->em->flush();
            $this->flashInfo("Deleted");
            return $this->redirectToRoute($this->indexRouteName);
        }

        $newPath = "admin/{$this->templatePath}/delete.html.twig";
        return $this->render($newPath, [
            'form' => $form->createView(),
            'menu' => $this->menu,
            'entity' => $entity,
            'indexRouteName' => $this->indexRouteName
        ]);
    }

    /**
     * Récupère le repository en fonction de l'entité définie.
     * @return EntityRepository
     */
    private function getRepository(): EntityRepository {
        /** @var EntityRepository $repository */
        $repository = $this->em->getRepository($this->entity);
        return $repository;
    }

    /**
     * Applique les traitements spécifiques lors de la création.
     * @param $entity
     */
    abstract protected function applyNew($entity): void;

    /**
     * Applique les traitements spécifiques lors de la modification.
     * @param $entity
     */
    abstract protected function applyEdit($entity): void;

}
