<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $api_url = env('CONTENT_GUARDIAN_URL');
        $api_key = env('CONTENT_GUARDIAN_API_KEY');
        $queryParams = $request->all();
        $pinnedNews = News::get();
        if (!empty($queryParams) || isset($queryParams)) {
            $api_url = $api_url . 'search?api-key=' . $api_key;
            foreach ($queryParams as $key => $value) {
                if ($key === 'search-input') {
                    $key = 'q';
                }
                $api_url .= "&$key=$value";
            }
        } else {
            $api_url = $api_url . 'search?api-key=' . $api_key;
        }
        $response = Http::get($api_url);
        $pagination = [
            "currentPage" => $response['response']['currentPage'],
            "pages" => $response['response']['pages'],
        ];
        $news = $response['response']['results'];
   
        return view('news', compact('news', 'pinnedNews','pagination','queryParams'));
    }
    public function pin(Request $request)
    {
        $pinned_article = News::create($request->all());
        return response()->json([
            'message' => 'news pinned successfully',
            'data' => $pinned_article
        ]);
    }
    public function unpin(Request $request)
    {
        $newsId = $request->input('newsId');
        $pinned_article = News::where('newsId', $newsId)->delete();
        return response()->json([
            'message' => 'news unpinned successfully',
            'data' => $pinned_article
        ]);
    }
}
