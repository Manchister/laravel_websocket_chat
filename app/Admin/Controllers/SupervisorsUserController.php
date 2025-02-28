<?php

namespace App\Admin\Controllers;

use App\Admin\Models\UserRole;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class SupervisorsUserController extends AdminController
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
        $userId = Admin::user()->id;
        $adminLevel = User::getUserLevel($userId);
        $grid = new Grid(new User());
        $grid->model()->where('user_level', '>', config('admin.admin_level.supervisor'));
        //var_dump(User::getSupervisorIdsByCreator($userId));
        $grid->disableCreateButton();
        if ($adminLevel == config('admin.admin_level.admin'))
        {
            $grid->model()->whereIn('created_by', User::getSupervisorIdsByCreator($userId));

        } elseif ($adminLevel == config('admin.admin_level.supervisor'))
        {
            $grid->disableCreateButton(false);
            $grid->model()->where('created_by', '=', $userId);
        }
        $grid->column('id', __('Id'));
        $grid->column('username', __('Username'))->hide();
        $grid->column('name', __('Name'))->hide();
        $grid->column('nick_name', __('Nick name'));
        if ($adminLevel != config('admin.admin_level.supervisor'))
        {
            $grid->column('created_by', __('Created By'))
                ->display(function($userId) {
                    return User::find($userId)->name;
                });
        }

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
        $userId = Admin::user()->id;
        $show = new Show(User::showUser($id, $userId));

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
        $form = new Form(new User());

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
        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];

        $form->multipleSelect('user_roles', __('admin.roles'))->options(UserRole::all()->pluck('name', 'id'));
        $form->ignore(['password_confirmation']);

        if ($form->isCreating())  {
            $form->email('email', trans('admin.email'))->rules('required');
            $form->password('password', trans('admin.password'))->rules('required|confirmed');
            $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required');

            $form->hidden('created_by');
            $form->hidden('user_level');
        }
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
            if ($form->isCreating())
            {
                $form->created_by = Admin::user()->id;
                $form->user_level = 4;
            };



        });

        return $form;
    }

    public function edit($id, Content $content)
    {
        $userId = Admin::user()->id;
        if (!User::canEditUser($id, $userId)) {
            return redirect(config('admin.route.prefix') . "/users");
        }
        return parent::edit($id, $content); // TODO: Change the autogenerated stub
    }
}
