<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckBookAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $bookId = $request->route('book_id'); // Pastikan ID buku tersedia di route parameter

        // Cek apakah pengguna sudah membeli eBook
        $hasAccess = DB::table('akses_buku')->where('user_id', $user->id)->where('book_id', $bookId)->exists();

        if (!$hasAccess) {
            return response()->json(['message' => 'Akses ditolak, Anda belum membeli buku ini.'], 403);
        }

        return $next($request);
    }
}
