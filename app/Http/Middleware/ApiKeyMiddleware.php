<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;

class ApiKeyMiddleware
{
public function handle(Request $request, Closure $next)
{
$apiKey = $request->header('X-API-KEY');

if (!$apiKey || !Client::where('api_key', $apiKey)->exists()) {
return response()->json(['error' => 'Unauthorized'], 401);
}

return $next($request);
}
}
