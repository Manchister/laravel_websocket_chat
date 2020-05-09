<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
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
    protected $fillable = ['name', 'nick_name', 'email', 'level_no', 'password', 'belongs_to_admin_id',];


    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->model()->where('belongs_to_admin_id', Admin::user()->id)
            ->orderBy('name', 'asc');
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('nick_name', __('Nick name'));
        $grid->column('admin_url', __('Admin url'));
        $grid->column('email', __('Email'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('password', __('Password'));
        $grid->column('level_no', __('Level no'));
        $grid->column('last_seen', __('Last seen'));
        $grid->column('remember_token', __('Remember token'));
        $grid->column('role.can_write', __('can write'));
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('nick_name', __('Nick name'));
        $show->field('admin_url', __('Admin url'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('level_no', __('Level no'));
        $show->field('last_seen', __('Last seen'));
        $show->field('remember_token', __('Remember token'));
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

        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];
        $form = new Form(new User());
        $form->text('name', __('Name'));
        $form->text('nick_name', __('Nick name'));
        //$form->text('admin_url', __('Admin url'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->readonly();
        $form->number('level_no', __('Level no'));
        $form->password('password', __('pass'));
        $form->hidden('admin_url');
        $form->hidden('belongs_to_admin_id');

        //$form->date('last_seen', __('Last seen'))->readonly();

        //$form->hidden('belongs_to')->value();
        $form->saving(function (Form $form) {
            $form->admin_url = Admin::user()->uri;
            $form->belongs_to_admin_id = Admin::user()->id;
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });


        return $form;
    }

}
