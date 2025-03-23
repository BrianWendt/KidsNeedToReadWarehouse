<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Util\RememberedParameter;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        $menus = [];
        $menus[] = Menu::make(__('Overview'))
            ->icon('bs.diagram-3')
            ->route('platform.index')
            ->permission('warehouse');

        // Inventory
        $menus[] = Menu::make(__('Books Database'))
            ->icon('bs.book')
            ->route('app.book.list')
            ->permission('warehouse')
            ->title(__('Inventory'));

        $menus[] = Menu::make(__('Purchase Orders'))
            ->icon('bs.card-list')
            ->route('app.purchase_order.list')
            ->permission('warehouse');

        if (RememberedParameter::getPurchaseOrderId()) {
            $menus[] = Menu::make(__('P.O. #').RememberedParameter::getPurchaseOrderId())
                ->icon('bs.upc-scan')
                ->route('app.purchase_order.view', RememberedParameter::getPurchaseOrderId())
                ->permission('warehouse');
        }

        $menus[] = Menu::make(__('Fulfillments'))
            ->icon('bs.box-seam')
            ->route('app.fulfillment.list')
            ->permission('warehouse');

        if (RememberedParameter::getFulfillmentId()) {
            $menus[] = Menu::make(__('Fulfillment #').RememberedParameter::getFulfillmentId())
                ->icon('bs.box2-heart')
                ->route('app.fulfillment.view', RememberedParameter::getFulfillmentId())
                ->permission('warehouse');
        }

        $menus[] = Menu::make(__('Missing Books'))
            ->icon('bs.question-square')
            ->route('app.inventory.missing')
            ->permission('warehouse');

        $menus[] = Menu::make(__('Audits'))
            ->icon('bs.file-earmark-check')
            ->route('app.audit.list')
            ->permission('warehouse');

        // Reports
        $menus[] = Menu::make(__('Inventory Report'))
            ->icon('bs.file-earmark-spreadsheet')
            ->route('app.reports.inventory')
            ->permission('warehouse')
            ->title(__('Reports'));

        // Contact Management
        $menus[] = Menu::make(__('Contacts'))
            ->icon('bs.person-rolodex')
            ->route('app.contact.list')
            ->permission('warehouse')
            ->title(__('Contact Management'));

        $menus[] = Menu::make(__('Organizations'))
            ->icon('bs.building')
            ->route('app.organization.list')
            ->permission('platform.systems.users');

        // Access Controls
        $menus[] = Menu::make(__('Users'))
            ->icon('bs.people')
            ->route('platform.systems.users')
            ->permission('platform.systems.users')
            ->title(__('Access Controls'));

        $menus[] = Menu::make(__('Roles'))
            ->icon('bs.lock')
            ->route('platform.systems.roles')
            ->permission('platform.systems.roles')
            ->divider();

        // System Config
        $menus[] = Menu::make(__('Programs'))
            ->icon('arrow-down-right-square')
            ->route('platform.resource.list', ['program-resources'])
            ->permission('warehouse')
            ->title(__('System Config'));

        $menus[] = Menu::make(__('Initiatives'))
            ->icon('arrow-down-right-square')
            ->route('platform.resource.list', ['initiative-resources'])
            ->permission('warehouse');

        return $menus;
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
            ItemPermission::group(__('Warehouse'))
                ->addPermission('warehouse', __('Warehouse')),
        ];
    }
}
