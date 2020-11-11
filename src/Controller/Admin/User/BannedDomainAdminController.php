<?php

namespace App\Controller\Admin\User;

use App\Controller\Admin\AdminAbstractController;
use App\Entity\User\BannedDomain;
use App\Form\Admin\User\BannedDomainFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/danger/banned-domain")
 */
class BannedDomainAdminController extends AdminAbstractController {

    protected $entity = BannedDomain::class;
    protected $templatePath = 'user/banned-domain';
    protected $menu = '';
    protected $formClassName = BannedDomainFormType::class;
    protected $indexRouteName = 'admin_banned_domain_index';
    protected $addRouteName = 'admin_banned_domain_add';
    protected $editRouteName = 'admin_banned_domain_edit';
    protected $deleteRouteName = 'admin_banned_domain_delete';

    /**
     * @Route("/", name="admin_banned_domain_index")
     * @return Response
     */
    public function index(): Response {
        return $this->abstractIndex();
    }

    /**
     * @Route("/creer", name="admin_banned_domain_add")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        return $this->abstractNew(new BannedDomain());
    }

    /**
     * @Route("/modifier/{id}", name="admin_banned_domain_edit")
     * @param string $id identifiant de l'article à modifier
     * @return Response
     */
    public function edit(string $id): Response {
        return $this->abstractEdit($id);
    }

    /**
     * @Route("/supprimer/{id}", name="admin_banned_domain_delete")
     * @param string $id identifiant de l'article à supprimer
     * @return Response
     */
    public function delete(string $id): Response {
        return $this->abstractDelete($id);
    }

    /**
     * @inheritDoc
     */
    protected function applyNew($entity): void {
        // Empty
    }

    /**
     * @inheritDoc
     */
    protected function applyEdit($entity): void {
        // Empty
    }

}