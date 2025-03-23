<?php

declare(strict_types=1);

use App\Orchid\Screens;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Main
Route::screen('/dashboard', Screens\PlatformScreen::class)
    ->name('platform.dashboard');

// Platform > Profile
Route::screen('profile', Screens\User\UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

/**
 * User Management
 */

// Platform > System > Users > User
Route::screen('users/{user}/edit', Screens\User\UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', Screens\User\UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', Screens\User\UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', Screens\Role\RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', Screens\Role\RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', Screens\Role\RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

/**
 * Books
 */

// App > Books
Route::screen('books', Screens\Book\BookListScreen::class)
    ->name('app.book.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Books'), route('app.book.list')));

// App > Books > Edit Book
Route::screen('book/{book}/edit', Screens\Book\BookEditScreen::class)
    ->name('app.book.edit')
    ->breadcrumbs(fn (Trail $trail, $book) => $trail
        ->parent('app.book.list')
        ->push($book->title, route('app.book.edit', $book)));

// App > Books > Create Book
Route::screen('book/create/{isbn}', Screens\Book\BookAddScreen::class)
    ->name('app.book.create')
    ->breadcrumbs(fn (Trail $trail, $isbn) => $trail
        ->parent('app.book.list')
        ->push(__('Create'), route('app.book.create', $isbn)));

/**
 * Inventory
 */

// App > Inventory Lookup
Route::screen('inventory/lookup', Screens\Inventory\InventoryLookupScreen::class)
    ->name('app.inventory.lookup')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Inventory'), route('app.inventory.lookup')));

// App > Inventory > Record
Route::screen('inventory/{isbn}/record', Screens\Inventory\InventoryRecordScreen::class)
    ->name('app.inventory.record')
    ->breadcrumbs(fn (Trail $trail, $isbn) => $trail
        ->parent('app.inventory.lookup')
        ->push($isbn, route('app.inventory.record', $isbn)));

// App > Inventory > Edit
Route::screen('inventory/{inventory}/edit', Screens\Inventory\InventoryEditScreen::class)
    ->name('app.inventory.edit')
    ->breadcrumbs(fn (Trail $trail, $inventory) => $trail
        ->parent('app.inventory.record', $inventory->isbn)
        ->push(__('Edit'), route('app.inventory.edit', $inventory)));

// App > Inventory > Missing Books
Route::screen('inventory/missing', Screens\Inventory\MissingBooksScreen::class)
    ->name('app.inventory.missing')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('app.book.list')
        ->push('Missing Books', route('app.inventory.missing')));

/**
 * Audit
 */
Route::screen('audits', Screens\Audit\AuditListScreen::class)
    ->name('app.audit.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Audits'), route('app.audit.list')));

// App > Audit > Inventory > Record
Route::screen('audit/{audit}/record', Screens\Audit\AuditInventoryRecordScreen::class)
    ->name('app.audit.record')
    ->breadcrumbs(
        fn (Trail $trail, $audit) => $trail
            ->parent('app.audit.list')
            ->parent('app.audit.view', $audit)
            ->push('Record', route('app.audit.record', $audit))
    );

// App > Audits >  Create
Route::screen('audit/create', Screens\Audit\AuditEditScreen::class)
    ->name('app.audit.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('app.audit.list')
        ->push(__('Create'), route('app.audit.create')));

// App > Audits > View
Route::screen('audit/{audit}', Screens\Audit\AuditViewScreen::class)
    ->name('app.audit.view')
    ->breadcrumbs(fn (Trail $trail, $audit) => $trail
        ->parent('app.audit.list')
        ->push($audit->label, route('app.audit.view', $audit->id)));

// App > Audits > Edit
Route::screen('audit/{audit}/edit', Screens\Audit\AuditEditScreen::class)
    ->name('app.audit.edit')
    ->breadcrumbs(fn (Trail $trail, $audit) => $trail
        ->parent('app.audit.view', $audit)
        ->push(__('Edit'), route('app.audit.edit', $audit)));

/**
 * Contact Management
 */

// App > Contacts
Route::screen('contacts', Screens\Contact\ContactListScreen::class)
    ->name('app.contact.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Contacts'), route('app.contact.list')));

// App > Contacts >  Create
Route::screen('contact/create', Screens\Contact\ContactCreateScreen::class)
    ->name('app.contact.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('app.contact.list')
        ->push(__('Create'), route('app.contact.create')));

// App > Contacts > View
Route::screen('contact/{contact}', Screens\Contact\ContactViewScreen::class)
    ->name('app.contact.view')
    ->breadcrumbs(fn (Trail $trail, $contact) => $trail
        ->parent('app.contact.list')
        ->push($contact->full_name, route('app.contact.view', $contact->id)));

// App > Contacts > Edit
Route::screen('contact/{contact}/edit', Screens\Contact\ContactEditScreen::class)
    ->name('app.contact.edit')
    ->breadcrumbs(fn (Trail $trail, $contact) => $trail
        ->parent('app.contact.view', $contact)
        ->push(__('Edit'), route('app.contact.edit', $contact)));

// App > Contacts > Address > Create
Route::screen('address/create', Screens\Address\AddressCreateScreen::class)
    ->name('app.address.create')
    ->breadcrumbs(fn (Trail $trail, $address) => $trail
        ->parent('app.contact.view', \App\Models\Contact::find(request()->query('contact_id')))
        ->push(__('Add Address'), route('app.address.create', $address)));

// App > Contacts > Address > Edit
Route::screen('address/{address}/edit', Screens\Address\AddressEditScreen::class)
    ->name('app.address.edit')
    ->breadcrumbs(
        fn (Trail $trail, $address) => $trail
            ->parent('app.contact.view', $address->contact)
            ->push($address->name, route('app.address.edit', $address))
    );

// App > Contacts > Email > Create
Route::screen('email/create', Screens\Email\EmailCreateScreen::class)
    ->name('app.email.create')
    ->breadcrumbs(fn (Trail $trail, $email) => $trail
        ->parent('app.contact.view', \App\Models\Contact::find(request()->query('contact_id')))
        ->push(__('Add Email Address'), route('app.email.create', $email)));

// App > Contacts > Email > Edit
Route::screen('email/{email}/edit', Screens\Email\EmailEditScreen::class)
    ->name('app.email.edit')
    ->breadcrumbs(fn (Trail $trail, $email) => $trail
        ->parent('app.contact.view', $email->contact)
        ->push($email->name, route('app.email.edit', $email)));

// App > Contacts > Telephone > Create
Route::screen('telephone/create', Screens\Telephone\TelephoneCreateScreen::class)
    ->name('app.telephone.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('app.contact.view', \App\Models\Contact::find(request()->query('contact_id')))
        ->push(__('Add Telephone Number'), route('app.telephone.create')));

// App > Contacts > Telephone > Edit
Route::screen('telephone/{telephone}/edit', Screens\Telephone\TelephoneEditScreen::class)
    ->name('app.telephone.edit')
    ->breadcrumbs(
        fn (Trail $trail, $telephone) => $trail
            ->parent('app.contact.view', $telephone->contact)
            ->push($telephone->name, route('app.telephone.edit', $telephone))
    );

// App > Organizations
Route::screen('organizations', Screens\Organization\OrganizationListScreen::class)
    ->name('app.organization.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Organizations'), route('app.organization.list')));

// App > Organizations > Edit Organization
Route::screen('organization/{organization}/edit', Screens\Organization\OrganizationEditScreen::class)
    ->name('app.organization.edit')
    ->breadcrumbs(fn (Trail $trail, $organization) => $trail
        ->parent('app.organization.list')
        ->push($organization->name, route('app.organization.edit', $organization)));

// App > Organizations > View Organization
Route::screen('organization/{organization}/view', Screens\Organization\OrganizationViewScreen::class)
    ->name('app.organization.view')
    ->breadcrumbs(fn (Trail $trail, $organization) => $trail
        ->parent('app.organization.list')
        ->push($organization->name, route('app.organization.view', $organization)));

// App > Organizations > Create Organization
Route::screen('organization/create', Screens\Organization\OrganizationCreateScreen::class)
    ->name('app.organization.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('app.organization.list')
        ->push(__('Create'), route('app.organization.create')));
/**
 * PurchaseOrders
 */

// App > PurchaseOrders
Route::screen('purchase_orders', Screens\PurchaseOrder\PurchaseOrderListScreen::class)
    ->name('app.purchase_order.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Purchase Orders'), route('app.purchase_order.list')));

// App > PurchaseOrders >  Create
Route::screen('purchase_order/create', Screens\PurchaseOrder\PurchaseOrderCreateScreen::class)
    ->name('app.purchase_order.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('app.purchase_order.list')
        ->push(__('Create'), route('app.purchase_order.create')));

// App > PurchaseOrders > View
Route::screen('purchase_order/{purchase_order}', Screens\PurchaseOrder\PurchaseOrderViewScreen::class)
    ->name('app.purchase_order.view')
    ->breadcrumbs(fn (Trail $trail, $purchase_order) => $trail
        ->parent('app.purchase_order.list')
        ->push($purchase_order->display, route('app.purchase_order.view', $purchase_order->id)));

// App > PurchaseOrders > Print
Route::screen('purchase_order/{purchase_order}/print', Screens\PurchaseOrder\PurchaseOrderPrintScreen::class)
    ->name('app.purchase_order.print')
    ->breadcrumbs(fn (Trail $trail, $purchase_order) => $trail
        ->parent('app.purchase_order.view', $purchase_order)
        ->push('Print', route('app.purchase_order.print', $purchase_order->id)));

// App > PurchaseOrders > Edit
Route::screen('purchase_order/{purchase_order}/edit', Screens\PurchaseOrder\PurchaseOrderEditScreen::class)
    ->name('app.purchase_order.edit')
    ->breadcrumbs(fn (Trail $trail, $purchase_order) => $trail
        ->parent('app.purchase_order.view', $purchase_order)
        ->push(__('Edit'), route('app.purchase_order.edit', $purchase_order)));

/**
 * Fulfillments
 */

// App > Fulfillments
Route::screen('fulfillments', Screens\Fulfillment\FulfillmentListScreen::class)
    ->name('app.fulfillment.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Fulfillments'), route('app.fulfillment.list')));

// App > Fulfillments >  Create
Route::screen('fulfillment/create', Screens\Fulfillment\FulfillmentCreateScreen::class)
    ->name('app.fulfillment.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('app.fulfillment.list')
        ->push(__('Create'), route('app.fulfillment.create')));

// App > Fulfillments > View
Route::screen('fulfillment/{fulfillment}', Screens\Fulfillment\FulfillmentViewScreen::class)
    ->name('app.fulfillment.view')
    ->breadcrumbs(fn (Trail $trail, $fulfillment) => $trail
        ->parent('app.fulfillment.list')
        ->push($fulfillment->display, route('app.fulfillment.view', $fulfillment->id)));

// App > Fulfillments > View
Route::screen('fulfillment/{fulfillment}/print', Screens\Fulfillment\FulfillmentPrintScreen::class)
    ->name('app.fulfillment.print')
    ->breadcrumbs(fn (Trail $trail, $fulfillment) => $trail
        ->parent('app.fulfillment.view', $fulfillment)
        ->push('Print', route('app.fulfillment.view', $fulfillment->id)));

// App > Fulfillments > Edit
Route::screen('fulfillment/{fulfillment}/edit', Screens\Fulfillment\FulfillmentEditScreen::class)
    ->name('app.fulfillment.edit')
    ->breadcrumbs(fn (Trail $trail, $fulfillment) => $trail
        ->parent('app.fulfillment.view', $fulfillment)
        ->push(__('Edit'), route('app.fulfillment.edit', $fulfillment)));

// App > Fulfillment Inventory > Delete
Route::screen('fulfillment_inventory/{fulfillment_inventory}/edit', Screens\FulfillmentInventory\FulfillmentInventoryEditScreen::class)
    ->name('app.fulfillment_inventory.edit');

/**
 * Reports
 */
Route::screen('reports/inventory', Screens\Reports\InventoryReport::class)
    ->name('app.reports.inventory');
