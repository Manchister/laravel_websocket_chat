<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

class SupervisorController extends AdminController
{


    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $userModel = config('admin.database.users_model');
        $userId = Admin::user()->id;
        $adminLevel = User::getUserLevel($userId);
        $grid = new Grid(new $userModel());
        $grid->model()->where('user_level', '=', config('admin.admin_level.supervisor'));

        if ($adminLevel == config('admin.admin_level.admin')) {
            $grid->model()->where('created_by', '=', $userId);
        };

        $grid->column('id', __('Id'));
        $grid->column('username', __('Username'))->hide();
        $grid->column('name', __('Name'))->hide();
        $grid->column('nick_name', __('Nick name'));
        $grid->column('created_by', __('Created By'))
            ->display(function ($userId) {
                return User::find($userId)->name;
            });
        /*$grid->director()->display(function($userId) {
            return User::find($userId)->name;
        });*/

        $grid->column('last_seen', __('Last seen'));
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $userModel = config('admin.database.users_model');
        $userId = Admin::user()->id;
        $show = new Show($userModel::showAdmin(config('admin.admin_level.supervisor'), $id, $userId));

        $show->field('id', __('Id'));
        $show->field('username', __('Username'));
        $show->field('name', __('Name'));
        $show->field('nick_name', __('Nick name'));
        $show->field('avatar', __('Avatar'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $userModel = config('admin.database.users_model');
        $form = new Form(new $userModel());

        $userTable = config('admin.database.users_table');
        $adminUrisTable = config('admin.database.admin_uris_table');
        $connection = config('admin.database.connection');

        $form->display('id', 'ID');
        $form->text('username', trans('admin.username'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        $form->text('name', trans('admin.name'))->rules('required');
        $form->text('nick_name', trans('admin.nick_name'))->rules('required');

        $form->image('avatar', trans('admin.avatar'));


        $form->ignore(['password_confirmation']);

        if ($form->isEditing()) {
            $form->text('admin_uri.uri', __('Uri'))->rules('required')
                ->updateRules(['required', "unique:{$connection}.{$adminUrisTable},uri,{{id}}"]);
        } else {
            $form->password('password', trans('admin.password'))->rules('required|confirmed');
            $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required');
            $form->multipleSelect('roles', trans('admin.roles'))->setDisplay(false);
            $form->hidden('admin_uri.uri');
            $form->hidden('created_by');
            $form->hidden('email');
            $form->hidden('user_level');
            $form->hidden('is_admin');
            $form->multipleSelect('roles')->setDisplay(false);
        }
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
            if ($form->isCreating()) {
                $form->roles = ["4",null];
                $form->created_by = Admin::user()->id;
                if (DB::table('admin_uris')->where('uri', $form->username)->count() <= 0) {
                    $form->input('admin_uri.uri', $form->username);
                } else {
                    $random = Str::random(10);
                    $form->input('admin_uri.uri', $form->username . "_" . $random);
                }
                $form->is_admin = true;
                $form->email = 'admins@admins.email';
                $form->user_level = config('admin.admin_level.supervisor');
                $form->roles = ["4",null];
            };


        });

        return $form;
    }

    public function edit($id, Content $content)
    {
        $userModel = config('admin.database.users_model');
        $userId = Admin::user()->id;
        if (!$userModel::canEdit(config('admin.admin_level.supervisor'), $id, $userId)) {
            return redirect(config('admin.route.prefix') . "/supervisors");
        }
        return parent::edit($id, $content); // TODO: Change the autogenerated stub
    }
}
