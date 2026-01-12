<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogFileOperations
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('files')) {
            $files = array_map(fn($f) => $f->getClientOriginalName(), $request->file('files'));
            Log::info('Uploaded files: ' . json_encode($files));
        }

        return $next($request);
    }
}
