<?php

namespace LaveroAdmin\Admin\Seo\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaveroAdmin\Admin\Facades\Admin;
use LaveroAdmin\Admin\Layout\Content;

class RobotsController extends Controller
{
    public function index(): Content
    {
        return Admin::content(function (Content $content) {
            $content->header('robots.txt');
            $content->description(trans('lavero-admin-seo::seo.robots_desc'));

            $path = public_path('robots.txt');
            $robotsContent = file_exists($path) ? (string) @file_get_contents($path) : "User-agent: *\nDisallow:\n";

            $content->body(view('lavero-admin-seo::robots', [
                'robotsContent' => $robotsContent,
                'robotsPath'    => $path,
                'writable'      => file_exists($path) ? is_writable($path) : is_writable(dirname($path)),
            ])->render());
        });
    }

    public function save(Request $request): RedirectResponse
    {
        $path = public_path('robots.txt');
        $body = str_replace("\r\n", "\n", (string) $request->input('content', ''));

        if (@file_put_contents($path, $body) === false) {
            admin_toastr(trans('lavero-admin-seo::seo.robots_write_error'), 'error');
        } else {
            admin_toastr(trans('lavero-admin-seo::seo.robots_saved'), 'success');
        }

        return redirect()->route('seo.robots');
    }
}
