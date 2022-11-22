<?php

namespace WorkStationDB\project3;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpNotFoundException;

// Handle Routes
return function (App $app) {
    // workstation
    $app->map(["GET","POST","OPTIONS"], '/workstationlist[/{id}]', WorkstationController::class . ':list')->add(PermissionMiddleware::class)->setName('workstationlist-workstation-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/workstationadd[/{id}]', WorkstationController::class . ':add')->add(PermissionMiddleware::class)->setName('workstationadd-workstation-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/workstationview[/{id}]', WorkstationController::class . ':view')->add(PermissionMiddleware::class)->setName('workstationview-workstation-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/workstationedit[/{id}]', WorkstationController::class . ':edit')->add(PermissionMiddleware::class)->setName('workstationedit-workstation-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/workstationupdate', WorkstationController::class . ':update')->add(PermissionMiddleware::class)->setName('workstationupdate-workstation-update'); // update
    $app->map(["GET","POST","OPTIONS"], '/workstationdelete[/{id}]', WorkstationController::class . ':delete')->add(PermissionMiddleware::class)->setName('workstationdelete-workstation-delete'); // delete
    $app->group(
        '/workstation',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', WorkstationController::class . ':list')->add(PermissionMiddleware::class)->setName('workstation/list-workstation-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', WorkstationController::class . ':add')->add(PermissionMiddleware::class)->setName('workstation/add-workstation-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', WorkstationController::class . ':view')->add(PermissionMiddleware::class)->setName('workstation/view-workstation-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', WorkstationController::class . ':edit')->add(PermissionMiddleware::class)->setName('workstation/edit-workstation-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('UPDATE_ACTION') . '', WorkstationController::class . ':update')->add(PermissionMiddleware::class)->setName('workstation/update-workstation-update-2'); // update
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', WorkstationController::class . ':delete')->add(PermissionMiddleware::class)->setName('workstation/delete-workstation-delete-2'); // delete
        }
    );

    // user
    $app->map(["GET","POST","OPTIONS"], '/userlist[/{id}]', UserController::class . ':list')->add(PermissionMiddleware::class)->setName('userlist-user-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/userview[/{id}]', UserController::class . ':view')->add(PermissionMiddleware::class)->setName('userview-user-view'); // view
    $app->group(
        '/user',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', UserController::class . ':list')->add(PermissionMiddleware::class)->setName('user/list-user-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', UserController::class . ':view')->add(PermissionMiddleware::class)->setName('user/view-user-view-2'); // view
        }
    );

    // component_type
    $app->map(["GET","POST","OPTIONS"], '/componenttypelist[/{id}]', ComponentTypeController::class . ':list')->add(PermissionMiddleware::class)->setName('componenttypelist-component_type-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/componenttypeadd[/{id}]', ComponentTypeController::class . ':add')->add(PermissionMiddleware::class)->setName('componenttypeadd-component_type-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/componenttypeaddopt', ComponentTypeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('componenttypeaddopt-component_type-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/componenttypeview[/{id}]', ComponentTypeController::class . ':view')->add(PermissionMiddleware::class)->setName('componenttypeview-component_type-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/componenttypeedit[/{id}]', ComponentTypeController::class . ':edit')->add(PermissionMiddleware::class)->setName('componenttypeedit-component_type-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/componenttypedelete[/{id}]', ComponentTypeController::class . ':delete')->add(PermissionMiddleware::class)->setName('componenttypedelete-component_type-delete'); // delete
    $app->group(
        '/component_type',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ComponentTypeController::class . ':list')->add(PermissionMiddleware::class)->setName('component_type/list-component_type-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ComponentTypeController::class . ':add')->add(PermissionMiddleware::class)->setName('component_type/add-component_type-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', ComponentTypeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('component_type/addopt-component_type-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ComponentTypeController::class . ':view')->add(PermissionMiddleware::class)->setName('component_type/view-component_type-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ComponentTypeController::class . ':edit')->add(PermissionMiddleware::class)->setName('component_type/edit-component_type-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ComponentTypeController::class . ':delete')->add(PermissionMiddleware::class)->setName('component_type/delete-component_type-delete-2'); // delete
        }
    );

    // component_category
    $app->map(["GET","POST","OPTIONS"], '/componentcategorylist[/{id}]', ComponentCategoryController::class . ':list')->add(PermissionMiddleware::class)->setName('componentcategorylist-component_category-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/componentcategoryadd[/{id}]', ComponentCategoryController::class . ':add')->add(PermissionMiddleware::class)->setName('componentcategoryadd-component_category-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/componentcategoryaddopt', ComponentCategoryController::class . ':addopt')->add(PermissionMiddleware::class)->setName('componentcategoryaddopt-component_category-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/componentcategoryview[/{id}]', ComponentCategoryController::class . ':view')->add(PermissionMiddleware::class)->setName('componentcategoryview-component_category-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/componentcategoryedit[/{id}]', ComponentCategoryController::class . ':edit')->add(PermissionMiddleware::class)->setName('componentcategoryedit-component_category-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/componentcategorydelete[/{id}]', ComponentCategoryController::class . ':delete')->add(PermissionMiddleware::class)->setName('componentcategorydelete-component_category-delete'); // delete
    $app->group(
        '/component_category',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ComponentCategoryController::class . ':list')->add(PermissionMiddleware::class)->setName('component_category/list-component_category-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ComponentCategoryController::class . ':add')->add(PermissionMiddleware::class)->setName('component_category/add-component_category-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', ComponentCategoryController::class . ':addopt')->add(PermissionMiddleware::class)->setName('component_category/addopt-component_category-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ComponentCategoryController::class . ':view')->add(PermissionMiddleware::class)->setName('component_category/view-component_category-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ComponentCategoryController::class . ':edit')->add(PermissionMiddleware::class)->setName('component_category/edit-component_category-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ComponentCategoryController::class . ':delete')->add(PermissionMiddleware::class)->setName('component_category/delete-component_category-delete-2'); // delete
        }
    );

    // component_make
    $app->map(["GET","POST","OPTIONS"], '/componentmakelist[/{id}]', ComponentMakeController::class . ':list')->add(PermissionMiddleware::class)->setName('componentmakelist-component_make-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/componentmakeadd[/{id}]', ComponentMakeController::class . ':add')->add(PermissionMiddleware::class)->setName('componentmakeadd-component_make-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/componentmakeaddopt', ComponentMakeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('componentmakeaddopt-component_make-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/componentmakeview[/{id}]', ComponentMakeController::class . ':view')->add(PermissionMiddleware::class)->setName('componentmakeview-component_make-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/componentmakeedit[/{id}]', ComponentMakeController::class . ':edit')->add(PermissionMiddleware::class)->setName('componentmakeedit-component_make-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/componentmakedelete[/{id}]', ComponentMakeController::class . ':delete')->add(PermissionMiddleware::class)->setName('componentmakedelete-component_make-delete'); // delete
    $app->group(
        '/component_make',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ComponentMakeController::class . ':list')->add(PermissionMiddleware::class)->setName('component_make/list-component_make-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ComponentMakeController::class . ':add')->add(PermissionMiddleware::class)->setName('component_make/add-component_make-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', ComponentMakeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('component_make/addopt-component_make-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ComponentMakeController::class . ':view')->add(PermissionMiddleware::class)->setName('component_make/view-component_make-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ComponentMakeController::class . ':edit')->add(PermissionMiddleware::class)->setName('component_make/edit-component_make-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ComponentMakeController::class . ':delete')->add(PermissionMiddleware::class)->setName('component_make/delete-component_make-delete-2'); // delete
        }
    );

    // component_model
    $app->map(["GET","POST","OPTIONS"], '/componentmodellist[/{id}]', ComponentModelController::class . ':list')->add(PermissionMiddleware::class)->setName('componentmodellist-component_model-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/componentmodeladd[/{id}]', ComponentModelController::class . ':add')->add(PermissionMiddleware::class)->setName('componentmodeladd-component_model-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/componentmodeladdopt', ComponentModelController::class . ':addopt')->add(PermissionMiddleware::class)->setName('componentmodeladdopt-component_model-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/componentmodelview[/{id}]', ComponentModelController::class . ':view')->add(PermissionMiddleware::class)->setName('componentmodelview-component_model-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/componentmodeledit[/{id}]', ComponentModelController::class . ':edit')->add(PermissionMiddleware::class)->setName('componentmodeledit-component_model-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/componentmodeldelete[/{id}]', ComponentModelController::class . ':delete')->add(PermissionMiddleware::class)->setName('componentmodeldelete-component_model-delete'); // delete
    $app->group(
        '/component_model',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ComponentModelController::class . ':list')->add(PermissionMiddleware::class)->setName('component_model/list-component_model-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ComponentModelController::class . ':add')->add(PermissionMiddleware::class)->setName('component_model/add-component_model-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', ComponentModelController::class . ':addopt')->add(PermissionMiddleware::class)->setName('component_model/addopt-component_model-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ComponentModelController::class . ':view')->add(PermissionMiddleware::class)->setName('component_model/view-component_model-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ComponentModelController::class . ':edit')->add(PermissionMiddleware::class)->setName('component_model/edit-component_model-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ComponentModelController::class . ':delete')->add(PermissionMiddleware::class)->setName('component_model/delete-component_model-delete-2'); // delete
        }
    );

    // component_display_size
    $app->map(["GET","POST","OPTIONS"], '/componentdisplaysizelist[/{id}]', ComponentDisplaySizeController::class . ':list')->add(PermissionMiddleware::class)->setName('componentdisplaysizelist-component_display_size-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/componentdisplaysizeadd[/{id}]', ComponentDisplaySizeController::class . ':add')->add(PermissionMiddleware::class)->setName('componentdisplaysizeadd-component_display_size-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/componentdisplaysizeaddopt', ComponentDisplaySizeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('componentdisplaysizeaddopt-component_display_size-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/componentdisplaysizeview[/{id}]', ComponentDisplaySizeController::class . ':view')->add(PermissionMiddleware::class)->setName('componentdisplaysizeview-component_display_size-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/componentdisplaysizeedit[/{id}]', ComponentDisplaySizeController::class . ':edit')->add(PermissionMiddleware::class)->setName('componentdisplaysizeedit-component_display_size-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/componentdisplaysizedelete[/{id}]', ComponentDisplaySizeController::class . ':delete')->add(PermissionMiddleware::class)->setName('componentdisplaysizedelete-component_display_size-delete'); // delete
    $app->group(
        '/component_display_size',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ComponentDisplaySizeController::class . ':list')->add(PermissionMiddleware::class)->setName('component_display_size/list-component_display_size-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ComponentDisplaySizeController::class . ':add')->add(PermissionMiddleware::class)->setName('component_display_size/add-component_display_size-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', ComponentDisplaySizeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('component_display_size/addopt-component_display_size-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ComponentDisplaySizeController::class . ':view')->add(PermissionMiddleware::class)->setName('component_display_size/view-component_display_size-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ComponentDisplaySizeController::class . ':edit')->add(PermissionMiddleware::class)->setName('component_display_size/edit-component_display_size-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ComponentDisplaySizeController::class . ':delete')->add(PermissionMiddleware::class)->setName('component_display_size/delete-component_display_size-delete-2'); // delete
        }
    );

    // component_keyboard_layout
    $app->map(["GET","POST","OPTIONS"], '/componentkeyboardlayoutlist[/{id}]', ComponentKeyboardLayoutController::class . ':list')->add(PermissionMiddleware::class)->setName('componentkeyboardlayoutlist-component_keyboard_layout-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/componentkeyboardlayoutadd[/{id}]', ComponentKeyboardLayoutController::class . ':add')->add(PermissionMiddleware::class)->setName('componentkeyboardlayoutadd-component_keyboard_layout-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/componentkeyboardlayoutaddopt', ComponentKeyboardLayoutController::class . ':addopt')->add(PermissionMiddleware::class)->setName('componentkeyboardlayoutaddopt-component_keyboard_layout-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/componentkeyboardlayoutview[/{id}]', ComponentKeyboardLayoutController::class . ':view')->add(PermissionMiddleware::class)->setName('componentkeyboardlayoutview-component_keyboard_layout-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/componentkeyboardlayoutedit[/{id}]', ComponentKeyboardLayoutController::class . ':edit')->add(PermissionMiddleware::class)->setName('componentkeyboardlayoutedit-component_keyboard_layout-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/componentkeyboardlayoutdelete[/{id}]', ComponentKeyboardLayoutController::class . ':delete')->add(PermissionMiddleware::class)->setName('componentkeyboardlayoutdelete-component_keyboard_layout-delete'); // delete
    $app->group(
        '/component_keyboard_layout',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ComponentKeyboardLayoutController::class . ':list')->add(PermissionMiddleware::class)->setName('component_keyboard_layout/list-component_keyboard_layout-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ComponentKeyboardLayoutController::class . ':add')->add(PermissionMiddleware::class)->setName('component_keyboard_layout/add-component_keyboard_layout-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', ComponentKeyboardLayoutController::class . ':addopt')->add(PermissionMiddleware::class)->setName('component_keyboard_layout/addopt-component_keyboard_layout-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ComponentKeyboardLayoutController::class . ':view')->add(PermissionMiddleware::class)->setName('component_keyboard_layout/view-component_keyboard_layout-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ComponentKeyboardLayoutController::class . ':edit')->add(PermissionMiddleware::class)->setName('component_keyboard_layout/edit-component_keyboard_layout-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ComponentKeyboardLayoutController::class . ':delete')->add(PermissionMiddleware::class)->setName('component_keyboard_layout/delete-component_keyboard_layout-delete-2'); // delete
        }
    );

    // login
    $app->map(["GET","POST","OPTIONS"], '/login[/{provider}]', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // logout
    $app->map(["GET","POST","OPTIONS"], '/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->get('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        if (Route_Action($app) === false) {
            return;
        }
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            throw new HttpNotFoundException($request, str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")));
        }
    );
};
