<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Services\Search\GlobalSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GlobalSearchController extends Controller
{
    public function __construct(private readonly GlobalSearchService $searchService)
    {
    }

    /**
     * Full search results page (works without JS, for direct links / no-JS fallback).
     */
    public function index(Request $request): View
    {
        $query = (string) $request->query('q', '');
        $results = $this->searchService->search($query);

        return view('search.index', compact('query', 'results'));
    }

    /**
     * JSON endpoint for the live-typing Alpine dropdown.
     */
    public function suggest(Request $request): JsonResponse
    {
        $query = (string) $request->query('q', '');
        $results = $this->searchService->search($query);

        return response()->json($results);
    }
}