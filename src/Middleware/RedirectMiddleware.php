<?php

namespace LaveroAdmin\Admin\Seo\Middleware;

use Closure;
use Illuminate\Http\Request;
use LaveroAdmin\Admin\Seo\Models\SeoRedirect;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $map = SeoRedirect::getCachedMap();
        if (empty($map)) {
            return $next($request);
        }

        $path = SeoRedirect::normalizePath($request->getPathInfo());
        if (!isset($map[$path])) {
            return $next($request);
        }

        $rule = $map[$path];
        $status = (int) ($rule['status_code'] ?? 301);

        try {
            SeoRedirect::where('id', $rule['id'])->update([
                'hits'        => \Illuminate\Support\Facades\DB::raw('hits + 1'),
                'last_hit_at' => now(),
            ]);
        } catch (\Throwable) {
        }

        if ($status === 410) {
            return response('Gone', 410);
        }

        $target = (string) ($rule['target_url'] ?? '/');

        return redirect()->to($target, in_array($status, [301, 302, 307, 308], true) ? $status : 301);
    }
}
