<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Campaign;
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;

class CampaignAdmin extends Admin
{
    // campaigns
    public const CAMPAIGN_LIST_VIEW = 'app.campaigns_list';
    public const CAMPAIGN_FORM_KEY = 'campaign_details';
    public const CAMPAIGN_ADD_FORM_VIEW = 'app.campaign_add_form';
    public const CAMPAIGN_EDIT_FORM_VIEW = 'app.campaign_edit_form';

    public function __construct(private readonly ViewBuilderFactoryInterface $viewBuilderFactory)
    {
    }

    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        $campaignCollectionsNavigationItem = new NavigationItem('Campaigns');
        $campaignCollectionsNavigationItem->setIcon('su-th-large');
        $campaignCollectionsNavigationItem->setPosition(28);
        $campaignCollectionsNavigationItem->setView(static::CAMPAIGN_LIST_VIEW);

        $navigationItemCollection->add($campaignCollectionsNavigationItem);
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $campaignssListView = $this->viewBuilderFactory->createListViewBuilder(static::CAMPAIGN_LIST_VIEW, '/campaigns')
            ->setResourceKey(Campaign::RESOURCE_KEY)
            ->setListKey('campaigns')
            ->addListAdapters(['table'])
            ->setAddView(static::CAMPAIGN_ADD_FORM_VIEW)
            ->setEditView(static::CAMPAIGN_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.add'),
                new ToolbarAction('sulu_admin.delete'),
                new ToolbarAction('sulu_admin.duplicate'), ]);

        $viewCollection->add($campaignssListView);

        $campaignsAddFormView = $this->viewBuilderFactory
            ->createResourceTabViewBuilder(static::CAMPAIGN_ADD_FORM_VIEW, '/campaigns/add')
            ->setResourceKey(Campaign::RESOURCE_KEY)
            ->setBackView(static::CAMPAIGN_LIST_VIEW);

        $viewCollection->add($campaignsAddFormView);

        $campaignsAddDetailsFormView = $this->viewBuilderFactory
            ->createFormViewBuilder(static::CAMPAIGN_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Campaign::RESOURCE_KEY)
            ->setFormKey(static::CAMPAIGN_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::CAMPAIGN_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save'), new ToolbarAction('sulu_admin.delete')])
            ->setParent(static::CAMPAIGN_ADD_FORM_VIEW);

        $viewCollection->add($campaignsAddDetailsFormView);

        $campaignsEditFormView = $this->viewBuilderFactory
            ->createResourceTabViewBuilder(static::CAMPAIGN_EDIT_FORM_VIEW, '/campaigns/:id')
            ->setResourceKey(Campaign::RESOURCE_KEY)
            ->setBackView(static::CAMPAIGN_LIST_VIEW);

        $viewCollection->add($campaignsEditFormView);

        $campaignsEditDetailsFormView = $this->viewBuilderFactory
            ->createFormViewBuilder(static::CAMPAIGN_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Campaign::RESOURCE_KEY)
            ->setFormKey(static::CAMPAIGN_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions([new ToolbarAction('sulu_admin.save'), new ToolbarAction('sulu_admin.delete')])
            ->setParent(static::CAMPAIGN_EDIT_FORM_VIEW);

        $viewCollection->add($campaignsEditDetailsFormView);
    }
}
