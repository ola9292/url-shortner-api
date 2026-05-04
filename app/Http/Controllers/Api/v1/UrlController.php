<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use App\Services\UrlTrimService;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function index()
    {
        return UrlResource::collection(Url::latest()->paginate(10));
    }

    public function store(Request $request, UrlTrimService $urlService)
    {

        $validated = $request->validate([
            'url' => ['required', 'url'],
        ]);

        $original_url = $validated['url'];
        $existing_url = Url::where('original_url', $original_url)->first();
        if ($existing_url) {
            return response()->json([
                'message' => 'url already exists',
                'data' => new UrlResource($existing_url),
            ], 200);
        }
        $data = $urlService->shorten_url($original_url);

        return response()->json([
            'message' => 'url created succesfully',
            'data' => new UrlResource($data),
        ], 201);

    }

    public function show($hash)
    {
        $url = Url::where('hash', $hash)->first();
        if (! $url) {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }

        return response()->json([
            'data' => new UrlResource($url),
        ], 200);
    }

    public function resolve(Request $request, $hash, UrlTrimService $urlService)
    {
        $url = $urlService->redirectUrl(
            $hash,
            $request->ip(),
            $request->userAgent()
        );
        if (! $url) {
            return response()->json([
                'message' => 'Resource not found!',
            ], 404);
        }

        return redirect($url->original_url);
    }

    public function showAnalytics($hash)
    {
        $url = Url::where('hash', $hash)->firstOrFail();
        $url->loadCount('clicks');

        $time_series = $url->clicks()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $uniqueVisitors = $url->clicks()
            ->distinct()
            ->count('ip_address');

        return response()->json([
            'id' => $url->id,
            'original_url' => $url->original_url,
            'short_url' => url('/').'/'.$url->hash,
            'total_clicks' => $url->clicks_count,
            'unique_visitors' => $uniqueVisitors,
            'time_series' => $time_series,
        ], 200);
    }
}
