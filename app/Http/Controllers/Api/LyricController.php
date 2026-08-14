<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class LyricController extends Controller
{
    public function searchSong(Request $request)
    {
        $search_term = $request->query('search_term');

        return Song::where('title', 'LIKE', '%'.$search_term.'%')->get();
    }

    //view song + lyrics
    public function viewSong($id)
    {
        try {
            if (Cache::has("song_{$id}")) {
                $song = Cache::get("song_{$id}");
            } else {
                $song = Song::where('id', $id)->first();

                if ($song) {
                    // Store in cache for 60 minutes
                    Cache::put("song_{$id}", $song, 60);
                }
            }

            if (! $song) {
                return response()->json(['message' => 'Song not found.'], 404);
            }

            return $this->transformSong($song);
        } catch (Throwable $exception) {
            Log::error('Lyrics detail fetch failed', $exception->getMessage());

            return response()->json(['message' => 'Failed to fetch song.'], 500);
        }
    }

    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');
        $category = (string) $request->query('category', '');
        $perPage = (int) $request->query('per_page', 20);
        $perPage = max(1, min(100, $perPage));

        $songs = Song::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('title', 'LIKE', '%'.$q.'%')
                        ->orWhere('author', 'LIKE', '%'.$q.'%');
                });
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->orderBy('title')
            ->paginate($perPage);

        $songs->getCollection()->transform(function ($song) {
            return $this->transformSong($song);
        });

        return response()->json($songs);
    }

    public function show($id)
    {
        $song = Song::find($id);
        if (! $song) {
            return response()->json(['message' => 'Song not found.'], 404);
        }

        return $this->transformSong($song);
    }

    public function search(Request $request)
    {
        $q = (string) $request->query('q', '');
        $limit = (int) $request->query('limit', 10);
        $limit = max(1, min(50, $limit));

        if (Str::length(trim($q)) < 2) {
            return response()->json([]);
        }

        $songs = Song::query()
            ->where('title', 'LIKE', '%'.$q.'%')
            ->orWhere('author', 'LIKE', '%'.$q.'%')
            ->orderBy('title')
            ->limit($limit)
            ->get();

        return response()->json($songs->map(function ($song) {
            return [
                'id' => $song->id,
                'title' => $song->title,
                'author' => $song->author,
                'category' => $song->category,
                'music_sheet_url' => $this->musicSheetUrl($song->music_sheet),
            ];
        }));
    }

    public function categories()
    {
        $categories = Category::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    public function siteConfig()
    {
        $siteLogo = Setting::get('site_logo');

        return response()->json([
            'site_name' => Setting::get('site_name'),
            'site_description' => Setting::get('site_description'),
            'site_logo_url' => $siteLogo ? asset(str_replace('public/', 'storage/', $siteLogo)) : null,
            'social' => [
                'facebook' => Setting::get('social_facebook'),
                'twitter' => Setting::get('social_twitter'),
                'instagram' => Setting::get('social_instagram'),
            ],
            'maintenance_mode' => Setting::get('maintenance_mode', '0') === '1',
        ]);
    }

    public function massSelection(Request $request)
    {
        $parts = [
            'Entrance' => $request->input('entrance', []),
            'Kyrie' => $request->input('kyrie', []),
            'Gloria' => $request->input('gloria', []),
            'Credo' => $request->input('credo', []),
            'Offertory' => $request->input('offertory', []),
            'Consecration' => $request->input('consecration', []),
            'Sanctus' => $request->input('sanctus', []),
            'Agnus Dei' => $request->input('agnus_dei', []),
            'Communion' => $request->input('communion', []),
            'Dismissal' => $request->input('dismissal', []),
        ];

        $content = "SELECTION FOR MASS\n\n";

        foreach ($parts as $label => $songIds) {
            $ids = $this->normalizeIds($songIds);

            $content .= strtoupper($label).":\n";
            if (count($ids) === 0) {
                $content .= "Not Found\n\n";
                $content .= "--------------------------------------------------\n\n";

                continue;
            }

            $foundAny = false;
            foreach ($ids as $id) {
                $song = Song::find($id);
                if (! $song) {
                    continue;
                }

                $foundAny = true;
                $content .= $song->title."\n";
                $content .= trim($this->versesToText($song->verses))."\n\n";
            }

            if (! $foundAny) {
                $content .= "Not Found\n\n";
            }

            $content .= "--------------------------------------------------\n\n";
        }

        $filename = 'mass_selection_'.date('Y-m-d_H-i').'.txt';

        if ((string) $request->query('download') === '1') {
            return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        }

        return response()->json([
            'filename' => $filename,
            'content' => $content,
        ]);
    }

    public function openapi()
    {
        $appUrl = rtrim((string) config('app.url', ''), '/');

        return response()->json([
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'Lit Lyrics API',
                'version' => '1.0.0',
                'description' => 'API for Lit Lyrics (songs, categories, site config, and mass selection). API keys are generated from the user dashboard after registration.',
            ],
            'servers' => [
                ['url' => $appUrl.'/api', 'description' => 'API base URL'],
            ],
            'security' => [
                ['ApiKeyAuth' => []],
            ],
            'paths' => [
                '/v1/auth/register' => [
                    'post' => [
                        'summary' => 'Register a new API client',
                        'security' => [],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'name' => ['type' => 'string', 'example' => 'Demo Client'],
                                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'client@example.com'],
                                        ],
                                        'required' => ['name', 'email'],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'Created',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'api_key' => ['type' => 'string'],
                                                'client' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'id' => ['type' => 'integer'],
                                                        'name' => ['type' => 'string'],
                                                        'email' => ['type' => 'string'],
                                                        'is_active' => ['type' => 'boolean'],
                                                        'subscription_required' => ['type' => 'boolean'],
                                                        'subscription_expires_at' => ['type' => 'string', 'format' => 'date-time', 'nullable' => true],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation Error',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string'],
                                                'errors' => [
                                                    'type' => 'object',
                                                    'additionalProperties' => [
                                                        'type' => 'array',
                                                        'items' => ['type' => 'string'],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/v1/auth/regenerate-key' => [
                    'post' => [
                        'summary' => 'Regenerate the API key',
                        'description' => 'Generates a new API key and invalidates the one used to make this request.',
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'api_key' => ['type' => 'string'],
                                                'message' => ['type' => 'string'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Unauthorized',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/v1/site-config' => [
                    'get' => [
                        'summary' => 'Get public site configuration',
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/SiteConfig'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/v1/categories' => [
                    'get' => [
                        'summary' => 'List categories',
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'array',
                                            'items' => ['$ref' => '#/components/schemas/Category'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/v1/songs' => [
                    'get' => [
                        'summary' => 'List songs',
                        'parameters' => [
                            [
                                'name' => 'q',
                                'in' => 'query',
                                'schema' => ['type' => 'string'],
                                'description' => 'Search by title or author.',
                            ],
                            [
                                'name' => 'category',
                                'in' => 'query',
                                'schema' => ['type' => 'string'],
                                'description' => 'Filter by category name.',
                            ],
                            [
                                'name' => 'per_page',
                                'in' => 'query',
                                'schema' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20],
                            ],
                            [
                                'name' => 'page',
                                'in' => 'query',
                                'schema' => ['type' => 'integer', 'minimum' => 1],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/PaginatedSongs'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Unauthorized',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                            '402' => [
                                'description' => 'Payment Required',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/v1/songs/search' => [
                    'get' => [
                        'summary' => 'Search songs (lightweight)',
                        'parameters' => [
                            [
                                'name' => 'q',
                                'in' => 'query',
                                'required' => true,
                                'schema' => ['type' => 'string'],
                            ],
                            [
                                'name' => 'limit',
                                'in' => 'query',
                                'schema' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'default' => 10],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'array',
                                            'items' => ['$ref' => '#/components/schemas/SongSearchResult'],
                                        ],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Unauthorized',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                            '402' => [
                                'description' => 'Payment Required',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/v1/songs/{id}' => [
                    'get' => [
                        'summary' => 'Get song by id',
                        'parameters' => [
                            [
                                'name' => 'id',
                                'in' => 'path',
                                'required' => true,
                                'schema' => ['type' => 'integer'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Song'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Unauthorized',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                            '402' => [
                                'description' => 'Payment Required',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                            '404' => [
                                'description' => 'Not Found',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/v1/mass-selection' => [
                    'post' => [
                        'summary' => 'Generate a mass selection text',
                        'parameters' => [
                            [
                                'name' => 'download',
                                'in' => 'query',
                                'schema' => ['type' => 'string', 'enum' => ['0', '1'], 'default' => '0'],
                                'description' => 'When set to 1, returns a downloadable text file.',
                            ],
                        ],
                        'requestBody' => [
                            'required' => false,
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/MassSelectionRequest'],
                                ],
                                'application/x-www-form-urlencoded' => [
                                    'schema' => ['$ref' => '#/components/schemas/MassSelectionRequest'],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/MassSelectionResponse'],
                                    ],
                                    'text/plain' => [
                                        'schema' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Unauthorized',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                            '402' => [
                                'description' => 'Payment Required',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Error'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'ApiKeyAuth' => [
                        'type' => 'apiKey',
                        'in' => 'header',
                        'name' => 'X-API-Key',
                    ],
                ],
                'schemas' => [
                    'Error' => [
                        'type' => 'object',
                        'properties' => [
                            'message' => ['type' => 'string'],
                        ],
                        'required' => ['message'],
                    ],
                    'Category' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'name' => ['type' => 'string'],
                        ],
                        'required' => ['id', 'name'],
                    ],
                    'Song' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'author' => ['type' => 'string', 'nullable' => true],
                            'category' => ['type' => 'string', 'nullable' => true],
                            'verses_html' => ['type' => 'string', 'nullable' => true],
                            'verses_text' => ['type' => 'string', 'nullable' => true],
                            'music_sheet_path' => ['type' => 'string', 'nullable' => true],
                            'music_sheet_url' => ['type' => 'string', 'nullable' => true],
                            'created_at' => ['type' => 'string', 'nullable' => true],
                            'updated_at' => ['type' => 'string', 'nullable' => true],
                        ],
                        'required' => ['id', 'title'],
                    ],
                    'SongSearchResult' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'author' => ['type' => 'string', 'nullable' => true],
                            'category' => ['type' => 'string', 'nullable' => true],
                            'music_sheet_url' => ['type' => 'string', 'nullable' => true],
                        ],
                        'required' => ['id', 'title'],
                    ],
                    'SiteConfig' => [
                        'type' => 'object',
                        'properties' => [
                            'site_name' => ['type' => 'string', 'nullable' => true],
                            'site_description' => ['type' => 'string', 'nullable' => true],
                            'site_logo_url' => ['type' => 'string', 'nullable' => true],
                            'social' => [
                                'type' => 'object',
                                'properties' => [
                                    'facebook' => ['type' => 'string', 'nullable' => true],
                                    'twitter' => ['type' => 'string', 'nullable' => true],
                                    'instagram' => ['type' => 'string', 'nullable' => true],
                                ],
                            ],
                            'maintenance_mode' => ['type' => 'boolean'],
                        ],
                        'required' => ['social', 'maintenance_mode'],
                    ],
                    'MassSelectionRequest' => [
                        'type' => 'object',
                        'properties' => [
                            'entrance' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'kyrie' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'gloria' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'credo' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'offertory' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'consecration' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'sanctus' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'agnus_dei' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'communion' => ['type' => 'array', 'items' => ['type' => 'integer']],
                            'dismissal' => ['type' => 'array', 'items' => ['type' => 'integer']],
                        ],
                    ],
                    'MassSelectionResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'filename' => ['type' => 'string'],
                            'content' => ['type' => 'string'],
                        ],
                        'required' => ['filename', 'content'],
                    ],
                    'PaginatedSongs' => [
                        'type' => 'object',
                        'properties' => [
                            'current_page' => ['type' => 'integer'],
                            'data' => [
                                'type' => 'array',
                                'items' => ['$ref' => '#/components/schemas/Song'],
                            ],
                            'first_page_url' => ['type' => 'string', 'nullable' => true],
                            'from' => ['type' => 'integer', 'nullable' => true],
                            'last_page' => ['type' => 'integer'],
                            'last_page_url' => ['type' => 'string', 'nullable' => true],
                            'links' => ['type' => 'array'],
                            'next_page_url' => ['type' => 'string', 'nullable' => true],
                            'path' => ['type' => 'string'],
                            'per_page' => ['type' => 'integer'],
                            'prev_page_url' => ['type' => 'string', 'nullable' => true],
                            'to' => ['type' => 'integer', 'nullable' => true],
                            'total' => ['type' => 'integer'],
                        ],
                        'required' => ['current_page', 'data', 'last_page', 'path', 'per_page', 'total'],
                    ],
                ],
            ],
        ]);
    }

    private function transformSong(Song $song): array
    {
        return [
            'id' => $song->id,
            'title' => $song->title,
            'author' => $song->author,
            'category' => $song->category,
            'verses_html' => $song->verses,
            'verses_text' => $this->versesToText($song->verses),
            'music_sheet_path' => $song->music_sheet,
            'music_sheet_url' => $this->musicSheetUrl($song->music_sheet),
            'created_at' => optional($song->created_at)->toISOString(),
            'updated_at' => optional($song->updated_at)->toISOString(),
        ];
    }

    private function versesToText(?string $verses): ?string
    {
        if ($verses === null) {
            return null;
        }

        $text = str_ireplace(['<br />', '<br>', '<br/>', '</p>'], "\n", $verses);
        $text = strip_tags($text);
        $text = html_entity_decode($text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim((string) $text);
    }

    private function musicSheetUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return asset(str_replace('public/', 'storage/', $path));
    }

    private function normalizeIds($value): array
    {
        if (is_string($value)) {
            $value = trim($value);
            if ($value === '' || $value === 'null') {
                return [];
            }
            $value = explode(',', $value);
        }

        if (! is_array($value)) {
            return [];
        }

        $ids = [];
        foreach ($value as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }
}
