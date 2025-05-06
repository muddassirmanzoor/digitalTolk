<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TranslationController extends Controller
{
    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="1.0.0",
     *         title="Translation Management API",
     *         description="API documentation for the Translation Management Service"
     *     ),
     *     @OA\Server(
     *         url=L5_SWAGGER_CONST_HOST,
     *         description="Localhost API server"
     *     )
     * )
     */


    /**
     * @OA\Get(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     summary="List translations with optional filters",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="locale", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="tags", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="key", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="value", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Success")
     * )
     */

    public function index(Request $request)
    {
        $locale = $request->input('locale', 'en');
        $pageSize = $request->input('page_size', 25); // Dynamically adjust the page size

        return Cache::remember("translations_locale_{$locale}", 60, function () use ($locale, $request, $pageSize) {
            $query = Translation::where('locale', $locale);

            if ($request->filled('tags')) {
                $query->whereJsonContains('tags', $request->input('tags'));
            }

            if ($request->filled('key')) {
                $query->where('key', 'like', "%{$request->input('key')}%");
            }

            return $query->paginate($pageSize);
        });
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'locale' => 'required|string',
            'key' => 'required|string|unique:translations,key',
            'value' => 'required|string',
            'tags' => 'nullable|array',
        ]);

        $translation = Translation::create($data);

        return response()->json($translation, 201);
    }

    public function update(Request $request, Translation $translation)
    {
        $data = $request->validate([
            'locale' => 'sometimes|required|string',
            'key' => 'sometimes|required|string|unique:translations,key,' . $translation->id,
            'value' => 'sometimes|required|string',
            'tags' => 'nullable|array',
        ]);

        $translation->update($data);

        return response()->json($translation);
    }

    public function show(Translation $translation)
    {
        return response()->json($translation);
    }

    public function export(Request $request)
    {
        $locale = $request->input('locale', 'en');
        $translations = Cache::remember("export_{$locale}", 5, function () use ($locale) {
            return Translation::where('locale', $locale)->get(['key', 'value'])->pluck('value', 'key');
        });

        return response()->json($translations);
    }
}
