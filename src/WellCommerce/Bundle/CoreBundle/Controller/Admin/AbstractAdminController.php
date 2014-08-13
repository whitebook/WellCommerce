<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace WellCommerce\Bundle\CoreBundle\Controller\Admin;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\CoreBundle\Controller\AbstractController;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\Manager\AdminManagerInterface;
use WellCommerce\Bundle\CoreBundle\DataGrid\DataGridInterface;
use WellCommerce\Bundle\CoreBundle\Form\FormInterface;
use WellCommerce\Bundle\CoreBundle\Repository\RepositoryInterface;

/**
 * Class AbstractAdminController
 *
 * @package WellCommerce\Bundle\CoreBundle\Controller
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractAdminController extends AbstractController implements AdminControllerInterface
{
    /**
     * @var DataGridInterface
     */
    protected $dataGrid;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var AdminManagerInterface
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param ContainerInterface    $container
     * @param RepositoryInterface   $repository
     * @param DataGridInterface     $dataGrid
     * @param FormInterface         $form
     * @param AdminManagerInterface $manager
     */
    public function __construct(
        ContainerInterface $container,
        AdminManagerInterface $manager,
        RepositoryInterface $repository,
        DataGridInterface $dataGrid,
        FormInterface $form
    ) {
        $this->setContainer($container);
        $this->repository = $repository;
        $this->dataGrid   = $dataGrid;
        $this->form       = $form;
        $this->manager    = $manager;
    }

    protected function getForm($resource)
    {
        return $this->getFormBuilder($this->form, $resource, [
            'name' => $this->repository->getAlias()
        ]);
    }

    public function indexAction()
    {
        return [
            'datagrid' => $this->dataGrid->get()
        ];
    }

    public function gridAction(Request $request)
    {
        return new JsonResponse($this->dataGrid->get()->load($request));
    }

    public function addAction(Request $request)
    {
        $resource = $this->repository->createNew();
        $form     = $this->getForm($resource);

        if ($form->handleRequest($request)->isValid()) {
            $this->manager->create($resource, $request);

            return $this->manager->getRedirectHelper()->redirectToAction('index');
        }

        return [
            'form' => $form
        ];
    }

    public function editAction(Request $request)
    {
        $resource = $this->repository->findResource($request);
        $form     = $this->getForm($resource);

        if ($form->handleRequest($request)->isValid()) {
            $this->manager->update($resource, $request);

            if ($form->isAction('continue')) {
                return $this->manager->getRedirectHelper()->redirectToAction('edit', $resource);
            }

            return $this->manager->getRedirectHelper()->redirectToAction('index');
        }

        return [
            'form' => $form
        ];
    }
}