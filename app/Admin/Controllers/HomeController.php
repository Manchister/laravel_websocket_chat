<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.isAdmin')->except('logout', 'getLogin');
    }
    public function index(Content $content)
    {
        return $content
            ->title('Dashboard')
            ->description('ChatRoom Dashboard')
//            ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(12, function (Column $column) {
                    $column->append('<div class="supervisor_link_div">
                <h3 class="supervisor_link_h">رابط غرف المشرف لتضمينه في مواقع أخرى</h3>
                <div>
                    <span>Link:</span>
                    <textarea rows="1" wrap="nowrap" class="supervisor_link_area" readonly>'.route('rooms',Auth::user()->username).'</textarea>
                </div>
                <div>
                    <span>Frame:</span>
                    <textarea rows="1" wrap="nowrap" class="supervisor_link_area" readonly><iframe src="'.route('rooms',Auth::user()->username).'"></iframe></textarea>
                </div>
            </div>');
                });

//                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::extensions());
//                });
//
//                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::dependencies());
//                });
            });
    }
}
