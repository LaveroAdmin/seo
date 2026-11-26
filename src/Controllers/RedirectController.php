<?php

namespace LaveroAdmin\Admin\Seo\Controllers;

use LaveroAdmin\Admin\Controllers\AdminController;
use LaveroAdmin\Admin\Form;
use LaveroAdmin\Admin\Grid;
use LaveroAdmin\Admin\Seo\Models\SeoRedirect;
use LaveroAdmin\Admin\Show;

class RedirectController extends AdminController
{
    protected function title()
    {
        return trans('lavero-admin-seo::seo.redirects');
    }

    protected function grid(): Grid
    {
        $grid = new Grid(new SeoRedirect());
        $grid->model()->orderBy('id', 'desc');

        $grid->column('id', __('admin.id'))->sortable();
        $grid->column('source_path', trans('lavero-admin-seo::seo.source_path'))->label('default');
        $grid->column('target_url', trans('lavero-admin-seo::seo.target_url'));
        $grid->column('status_code', trans('lavero-admin-seo::seo.status_code'))->using([
            301 => '301 Permanent',
            302 => '302 Temporary',
            307 => '307 Temporary',
            308 => '308 Permanent',
            410 => '410 Gone',
        ])->label([
            301 => 'success',
            302 => 'info',
            307 => 'info',
            308 => 'success',
            410 => 'danger',
        ]);
        $grid->column('is_active', trans('lavero-admin-seo::seo.is_active'))->switch();
        $grid->column('hits', trans('lavero-admin-seo::seo.hits'))->sortable();
        $grid->column('last_hit_at', trans('lavero-admin-seo::seo.last_hit_at'));

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('source_path', trans('lavero-admin-seo::seo.source_path'));
            $filter->like('target_url', trans('lavero-admin-seo::seo.target_url'));
            $filter->equal('status_code', trans('lavero-admin-seo::seo.status_code'))->select([
                301 => '301',
                302 => '302',
                410 => '410',
            ]);
        });

        return $grid;
    }

    protected function detail($id): Show
    {
        $show = new Show(SeoRedirect::findOrFail($id));

        $show->field('id', __('admin.id'));
        $show->field('source_path', trans('lavero-admin-seo::seo.source_path'));
        $show->field('target_url', trans('lavero-admin-seo::seo.target_url'));
        $show->field('status_code', trans('lavero-admin-seo::seo.status_code'));
        $show->field('is_active', trans('lavero-admin-seo::seo.is_active'));
        $show->field('hits', trans('lavero-admin-seo::seo.hits'));
        $show->field('last_hit_at', trans('lavero-admin-seo::seo.last_hit_at'));
        $show->field('note', trans('lavero-admin-seo::seo.note'));
        $show->field('created_at');
        $show->field('updated_at');

        return $show;
    }

    protected function form(): Form
    {
        $form = new Form(new SeoRedirect());

        $form->text('source_path', trans('lavero-admin-seo::seo.source_path'))->rules('required')->placeholder('/old-page');
        $form->text('target_url', trans('lavero-admin-seo::seo.target_url'))->placeholder('/new-page');
        $form->select('status_code', trans('lavero-admin-seo::seo.status_code'))->options([
            301 => '301 Permanent Redirect',
            302 => '302 Temporary Redirect',
            307 => '307 Temporary Redirect',
            308 => '308 Permanent Redirect',
            410 => '410 Gone (No target needed)',
        ])->default(301);
        $form->switch('is_active', trans('lavero-admin-seo::seo.is_active'))->default(1);
        $form->text('note', trans('lavero-admin-seo::seo.note'));

        return $form;
    }
}
