<?php

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\InfoGraphic;
use App\Models\PosModel;
use App\Models\Product;
use App\Models\Sector;
use App\Models\SubSector;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Breadcrumbs::for('admin.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Dashboard', route('admin.index'));
});
Breadcrumbs::for('user.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Dashboard', route('user.index'));
});
Breadcrumbs::for('admin.users.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Users', route('admin.users.index'));
});
Breadcrumbs::for('admin.user.customer', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Users', route('admin.user.customer'));
});
Breadcrumbs::for('admin.users.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.users.index');
    $trail->push('Add new user', route('admin.users.create'));
});
// Role
Breadcrumbs::for('admin.roles.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Roles', route('admin.roles.index'));
});
Breadcrumbs::for('admin.roles.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.roles.index');

    $trail->push('Add new role', route('admin.roles.create'));
});
Breadcrumbs::for('admin.roles.edit', function (BreadcrumbTrail $trail, Role $post): void {
    $trail->parent('admin.roles.index');

    $trail->push($post->name, route('admin.roles.edit', $post));
});
// Permission
Breadcrumbs::for('admin.permissions.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Permissions', route('admin.permissions.index'));
});
Breadcrumbs::for('admin.permissions.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.permissions.index');

    $trail->push('Add new permission', route('admin.permissions.create'));
});
Breadcrumbs::for('admin.permissions.edit', function (BreadcrumbTrail $trail, Permission $post): void {
    $trail->parent('admin.permissions.index');

    $trail->push($post->name, route('admin.permissions.edit', $post));
});
// profile
Breadcrumbs::for('admin.profile.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Profile', route('admin.profile.index'));
});
// change password
Breadcrumbs::for('admin.password.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Change Password', route('admin.password.index'));
});

// pos
Breadcrumbs::for('admin.pos_system.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('pos_system', route('admin.pos_system.index'));
});

Breadcrumbs::for('admin.pos_system.edit', function (BreadcrumbTrail $trail, $pos): void {
    $trail->parent('admin.pos_system.index');
    $trail->push('view', route('admin.pos_system.edit', $pos));
});

Breadcrumbs::for('admin.pos_system.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.pos_system.index');
    $trail->push('Add new pos_system', route('admin.pos_system.create'));
});
//dsr
Breadcrumbs::for('admin.dsr.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('dsr', route('admin.dsr'));
});
// sector
Breadcrumbs::for('admin.sector.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Sector', route('admin.sector.index'));
});

Breadcrumbs::for('admin.sector.edit', function (BreadcrumbTrail $trail, Sector $sector): void {
    $trail->parent('admin.sector.index');
    $trail->push('Edit', route('admin.sector.edit', $sector));
});

Breadcrumbs::for('admin.sector.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.sector.index');

    $trail->push('Add new Sector', route('admin.sector.create'));
});

//subsector
Breadcrumbs::for('admin.subsector.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Subsector', route('admin.subsector.index'));
});

Breadcrumbs::for('admin.subsector.edit', function (BreadcrumbTrail $trail, SubSector $subsector): void {
    $trail->parent('admin.subsector.index');
    $trail->push('Edit', route('admin.subsector.edit', $subsector));
});

Breadcrumbs::for('admin.subsector.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.subsector.index');

    $trail->push('Add new Subsector', route('admin.subsector.create'));
});

//blog
Breadcrumbs::for('admin.blog.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Blog', route('admin.blog.index'));
});

Breadcrumbs::for('admin.blog.edit', function (BreadcrumbTrail $trail, Blog $blog): void {
    $trail->parent('admin.blog.index');
    $trail->push('Edit', route('admin.blog.edit', $blog));
});

Breadcrumbs::for('admin.blog.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.blog.index');

    $trail->push('Add new Blog', route('admin.blog.create'));
});

//blog Category
Breadcrumbs::for('admin.blog_category.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('blog_category', route('admin.blog_category.index'));
});

Breadcrumbs::for('admin.blog_category.edit', function (BreadcrumbTrail $trail, BlogCategory $blog_category): void {
    $trail->parent('admin.blog_category.index');
    $trail->push('Edit', route('admin.blog_category.edit', $blog_category));
});

Breadcrumbs::for('admin.blog_category.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.blog_category.index');

    $trail->push('Add new blog_category', route('admin.blog_category.create'));
});

//Event Category
Breadcrumbs::for('admin.event_category.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('event_category', route('admin.event_category.index'));
});

Breadcrumbs::for('admin.event_category.edit', function (BreadcrumbTrail $trail, EventCategory $event_category): void {
    $trail->parent('admin.event_category.index');
    $trail->push('Edit', route('admin.event_category.edit', $event_category));
});

Breadcrumbs::for('admin.event_category.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.event_category.index');

    $trail->push('Add new event_category', route('admin.event_category.create'));
});

//Event 
Breadcrumbs::for('admin.event.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('event', route('admin.event.index'));
});

Breadcrumbs::for('admin.event.edit', function (BreadcrumbTrail $trail, Event $event): void {
    $trail->parent('admin.event.index');
    $trail->push('Edit', route('admin.event.edit', $event));
});

Breadcrumbs::for('admin.event.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.event.index');

    $trail->push('Add new Event', route('admin.event.create'));
});


//Info graphics
Breadcrumbs::for('admin.info_graphic.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('info_graphic', route('admin.info_graphic.index'));
});

Breadcrumbs::for('admin.info_graphic.edit', function (BreadcrumbTrail $trail, InfoGraphic $info_graphic): void {
    $trail->parent('admin.info_graphic.index');
    $trail->push('Edit', route('admin.info_graphic.edit', $info_graphic));
});

Breadcrumbs::for('admin.info_graphic.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.info_graphic.index');

    $trail->push('Add new info_graphic', route('admin.info_graphic.create'));
});

//Products
Breadcrumbs::for('admin.product.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('product', route('admin.product.index'));
});

Breadcrumbs::for('admin.product.edit', function (BreadcrumbTrail $trail, Product $product): void {
    $trail->parent('admin.product.index');
    $trail->push('Edit', route('admin.product.edit', $product));
});

Breadcrumbs::for('admin.product.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.product.index');

    $trail->push('Add new Product', route('admin.product.create'));
});
