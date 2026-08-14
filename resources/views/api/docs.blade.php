<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lit Lyrics - Developer API Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        /* CSS resets & custom variables */
        :root {
            --bg-sidebar: #0b0f19;
            --bg-main: #070a13;
            --bg-codebox: #111625;
            --bg-codeheader: #1b2234;
            --border-color: rgba(255, 255, 255, 0.07);
            
            --color-violet: #8b5cf6;
            --color-violet-hover: #7c3aed;
            --color-cyan: #06b6d4;
            --color-green: #10b981;
            --color-blue: #3b82f6;
            --color-red: #ef4444;
            
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        html {
            scroll-behavior: smooth;
            font-size: 15px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            line-height: 1.6;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar styling */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 100;
        }

        .logo-container {
            margin-bottom: 2.5rem;
        }

        .logo-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: 0.05em;
        }

        .logo-text span {
            color: var(--color-violet);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            font-size: 0.9rem;
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 2rem;
            transition: color 0.2s ease;
        }

        .back-btn:hover {
            color: var(--color-violet);
        }

        .back-btn svg {
            margin-right: 0.5rem;
            transition: transform 0.2s ease;
        }

        .back-btn:hover svg {
            transform: translateX(-3px);
        }

        .nav-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .nav-group-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            margin-bottom: 0.6rem;
        }

        .nav-sublist {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            padding-left: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
            color: var(--text-secondary);
            text-decoration: none;
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-primary);
            background: rgba(139, 92, 246, 0.08);
        }

        .nav-link.active {
            border-left: 3px solid var(--color-violet);
            border-radius: 0 6px 6px 0;
            padding-left: 0.4rem;
        }

        .method-tag {
            font-family: 'Fira Code', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .method-tag.get {
            background: rgba(16, 185, 129, 0.15);
            color: var(--color-green);
        }

        .method-tag.post {
            background: rgba(59, 130, 246, 0.15);
            color: var(--color-blue);
        }

        /* Main Container */
        .main-container {
            margin-left: 280px;
            flex-grow: 1;
            width: calc(100% - 280px);
        }

        /* Section Layout */
        .section {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            min-height: 400px;
        }

        .doc-column {
            width: 52%;
            padding: 4.5rem 3.5rem;
            overflow-y: auto;
        }

        .code-column {
            width: 48%;
            background: rgba(11, 15, 25, 0.3);
            border-left: 1px solid var(--border-color);
            padding: 4.5rem 2.5rem;
            position: relative;
        }

        /* Typography & elements */
        h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 0.8rem;
        }

        p {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 1.25rem;
            line-height: 1.7;
        }

        /* Code & URL styles */
        .url-box {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            gap: 0.8rem;
        }

        .url-path {
            color: var(--text-primary);
        }

        .base-label {
            color: var(--text-muted);
            font-size: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
        }

        /* Tables & Lists for parameters */
        .params-title {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        .params-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .param-item {
            display: flex;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            padding-bottom: 0.75rem;
        }

        .param-name-cell {
            width: 30%;
            flex-shrink: 0;
        }

        .param-name {
            font-family: 'Fira Code', monospace;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.85rem;
        }

        .param-type {
            font-size: 0.75rem;
            color: var(--color-cyan);
            margin-top: 0.2rem;
            display: block;
        }

        .param-desc-cell {
            width: 70%;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .required-badge {
            color: var(--color-red);
            font-size: 0.75rem;
            margin-left: 0.35rem;
            font-weight: bold;
        }

        .optional-badge {
            color: var(--text-muted);
            font-size: 0.72rem;
            margin-left: 0.35rem;
        }

        /* Right Panel Code Block styling */
        .code-box {
            background: var(--bg-codebox);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            margin-bottom: 1.5rem;
        }

        .code-header {
            background: var(--bg-codeheader);
            padding: 0.6rem 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .code-title {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .tabs {
            display: flex;
            gap: 0.5rem;
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            color: var(--text-primary);
        }

        .tab-btn.active {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
        }

        .code-body {
            position: relative;
        }

        pre {
            font-family: 'Fira Code', 'JetBrains Mono', monospace;
            font-size: 0.82rem;
            padding: 1.25rem;
            overflow-x: auto;
            color: #e2e8f0;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Syntax styling */
        .keyword { color: #f472b6; }
        .string { color: #34d399; }
        .number { color: #fb7185; }
        .comment { color: #64748b; }
        .property { color: #60a5fa; }
        .punctuation { color: #94a3b8; }
        .operator { color: #fbbf24; }

        .response-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding-left: 0.25rem;
        }

        .badge-status {
            font-family: 'Fira Code', monospace;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            background: rgba(16, 185, 129, 0.12);
            color: var(--color-green);
            display: inline-block;
            margin-left: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .section {
                flex-direction: column;
            }
            
            .doc-column, .code-column {
                width: 100%;
                padding: 3rem 2rem;
            }

            .code-column {
                border-left: none;
                border-top: 1px solid var(--border-color);
            }

            .sidebar {
                display: none;
            }

            .main-container {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('home') }}" class="logo-text">LIT-<span>LYRICS</span></a>
        </div>
        
        <a href="{{ route('home') }}" class="back-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Website
        </a>

        <nav>
            <ul class="nav-list">
                <li>
                    <div class="nav-group-title">Introduction</div>
                    <ul class="nav-sublist">
                        <li><a href="#overview" class="nav-link active">Overview</a></li>
                        <li><a href="#authentication" class="nav-link">Authentication</a></li>
                        <li><a href="#errors" class="nav-link">Error Handling</a></li>
                    </ul>
                </li>
                <li>
                    <div class="nav-group-title">Authentication Endpoints</div>
                    <ul class="nav-sublist">
                        <li>
                            <a href="#register" class="nav-link">
                                Register
                                <span class="method-tag post">POST</span>
                            </a>
                        </li>
                        <li>
                            <a href="#regenerate-key" class="nav-link">
                                Regenerate Key
                                <span class="method-tag post">POST</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <div class="nav-group-title">Metadata Endpoints</div>
                    <ul class="nav-sublist">
                        <li>
                            <a href="#site-config" class="nav-link">
                                Site Config
                                <span class="method-tag get">GET</span>
                            </a>
                        </li>
                        <li>
                            <a href="#categories" class="nav-link">
                                Categories
                                <span class="method-tag get">GET</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <div class="nav-group-title">Songs & Lyrics Endpoints</div>
                    <ul class="nav-sublist">
                        <li>
                            <a href="#list-songs" class="nav-link">
                                List Songs
                                <span class="method-tag get">GET</span>
                            </a>
                        </li>
                        <li>
                            <a href="#search-songs" class="nav-link">
                                Search Songs
                                <span class="method-tag get">GET</span>
                            </a>
                        </li>
                        <li>
                            <a href="#get-song" class="nav-link">
                                Get Song by ID
                                <span class="method-tag get">GET</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <div class="nav-group-title">Curation Endpoints</div>
                    <ul class="nav-sublist">
                        <li>
                            <a href="#mass-selection" class="nav-link">
                                Mass Selection
                                <span class="method-tag post">POST</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="main-container">
        
        <!-- SECTION: Overview -->
        <section id="overview" class="section">
            <div class="doc-column">
                <h1>API Reference</h1>
                <p>Welcome to the Lit Lyrics API developer portal. Our API enables seamless access to a rich catalog of liturgical lyrics, song categories, sheet music downloads, and tools to generate custom selections for Mass programs.</p>
                <p>The API is organized around REST. It accepts JSON-encoded request bodies, returns JSON-encoded responses, and uses standard HTTP response codes and authentication mechanisms.</p>
                
                <h3>Base URL</h3>
                <div class="url-box">
                    <span class="base-label">SECURE</span>
                    <span class="url-path">{{ url('/api') }}</span>
                </div>
                
                <h3>API Version</h3>
                <p>The current version of the API is <strong>v1</strong>. Standard resource endpoints are prefixed accordingly (e.g. <code>/v1/songs</code>).</p>
            </div>
            <div class="code-column">
                <!-- Visual divider/empty spacer for code side on landing section -->
            </div>
        </section>

        <!-- SECTION: Authentication -->
        <section id="authentication" class="section">
            <div class="doc-column">
                <h2>Authentication</h2>
                <p>Unless specified otherwise, all requests to the Lit Lyrics API require authentication. Authentication is handled via an API Key passed as a custom HTTP header.</p>
                
                <div class="url-box">
                    <span class="url-path">X-API-Key: your_api_key_here</span>
                </div>

                <p>You can generate an API key by signing up on the platform and registering a client application from the Developer settings of your Dashboard. Keep your API key secure—never expose it in client-side code, public repositories, or client browsers.</p>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Authentication Header Example</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="comment"># Include the key in your request headers</span>
GET /api/v1/songs HTTP/1.1
Host: {{ request()->getHost() }}
<strong>X-API-Key</strong>: lit_key_8a2d1f97b6c00e12...
Accept: application/json</code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: Error Handling -->
        <section id="errors" class="section">
            <div class="doc-column">
                <h2>Error Handling</h2>
                <p>Lit Lyrics uses conventional HTTP status codes to indicate the success or failure of an API request. In general:</p>
                <p>
                    • Codes in the <code>2xx</code> range indicate success.<br>
                    • Codes in the <code>4xx</code> range indicate an error that failed given the information provided (e.g., a required parameter was omitted, authentication failed, etc.).<br>
                    • Codes in the <code>5xx</code> range indicate an error with Lit Lyrics' servers.
                </p>
                
                <h3>HTTP Status Codes</h3>
                <div class="params-list" style="margin-top: 1rem;">
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">200 - OK</span>
                        </div>
                        <div class="param-desc-cell">The request succeeded.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">201 - Created</span>
                        </div>
                        <div class="param-desc-cell">Resource created successfully (e.g. registration).</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">400 - Bad Request</span>
                        </div>
                        <div class="param-desc-cell">The request was unacceptable, often due to missing required query parameters.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">401 - Unauthorized</span>
                        </div>
                        <div class="param-desc-cell">No valid API key provided or API key is inactive.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">402 - Payment Required</span>
                        </div>
                        <div class="param-desc-cell">Client requires an active subscription to access premium resources.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">404 - Not Found</span>
                        </div>
                        <div class="param-desc-cell">The requested resource could not be found.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">422 - Unprocessable</span>
                        </div>
                        <div class="param-desc-cell">Validation errors occurred in request body parameters.</div>
                    </div>
                </div>
            </div>
            <div class="code-column">
                <div class="response-title">Error Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Error Example</span>
                        <span class="badge-status" style="background: rgba(239, 68, 68, 0.12); color: var(--color-red);">401 UNAUTHORIZED</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">{</span>
  <span class="property">"message"</span><span class="punctuation">:</span> <span class="string">"Unauthorized. Invalid or missing X-API-Key header."</span>
<span class="punctuation">}</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: Register Client -->
        <section id="register" class="section">
            <div class="doc-column">
                <h2>Register Client</h2>
                <p>Register a new API client credentials. This endpoint is public and does not require an existing API Key. It is used to generate a fresh token for integrations.</p>
                
                <div class="url-box">
                    <span class="method-tag post">POST</span>
                    <span class="url-path">/v1/auth/register</span>
                </div>

                <div class="params-title">Request Body Parameters</div>
                <div class="params-list">
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">name</span>
                            <span class="required-badge">required</span>
                            <span class="param-type">string</span>
                        </div>
                        <div class="param-desc-cell">A human-readable name identifying your application (e.g. "Mobile App Client").</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">email</span>
                            <span class="required-badge">required</span>
                            <span class="param-type">string</span>
                        </div>
                        <div class="param-desc-cell">A contact email address associated with the client registration.</div>
                    </div>
                </div>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Request Sample</span>
                        <div class="tabs">
                            <button class="tab-btn active" data-lang="curl" onclick="switchLanguage('curl')">cURL</button>
                            <button class="tab-btn" data-lang="js" onclick="switchLanguage('js')">JS</button>
                            <button class="tab-btn" data-lang="python" onclick="switchLanguage('python')">Python</button>
                            <button class="tab-btn" data-lang="php" onclick="switchLanguage('php')">PHP</button>
                        </div>
                    </div>
                    <div class="code-body">
                        <!-- cURL -->
                        <pre class="tab-content curl active"><code>curl -X POST <span class="string">"{{ url('/api/v1/auth/register') }}"</span> \
  -H <span class="string">"Content-Type: application/json"</span> \
  -d <span class="string">'{
    "name": "Developer Demo Client",
    "email": "dev@example.com"
  }'</span></code></pre>
                        <!-- JS -->
                        <pre class="tab-content js"><code>fetch(<span class="string">"{{ url('/api/v1/auth/register') }}"</span>, {
  method: <span class="string">"POST"</span>,
  headers: {
    <span class="string">"Content-Type"</span>: <span class="string">"application/json"</span>
  },
  body: JSON.stringify({
    name: <span class="string">"Developer Demo Client"</span>,
    email: <span class="string">"dev@example.com"</span>
  })
})
.then(res => res.json())
.then(data => console.log(data));</code></pre>
                        <!-- Python -->
                        <pre class="tab-content python"><code>import requests

url = <span class="string">"{{ url('/api/v1/auth/register') }}"</span>
payload = {
    <span class="string">"name"</span>: <span class="string">"Developer Demo Client"</span>,
    <span class="string">"email"</span>: <span class="string">"dev@example.com"</span>
}

response = requests.post(url, json=payload)
print(response.json())</code></pre>
                        <!-- PHP -->
                        <pre class="tab-content php"><code>&lt;?php
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =&gt; <span class="string">"{{ url('/api/v1/auth/register') }}"</span>,
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_POST =&gt; true,
  CURLOPT_HTTPHEADER =&gt; [<span class="string">"Content-Type: application/json"</span>],
  CURLOPT_POSTFIELDS =&gt; json_encode([
    <span class="string">"name"</span> =&gt; <span class="string">"Developer Demo Client"</span>,
    <span class="string">"email"</span> =&gt; <span class="string">"dev@example.com"</span>
  ])
]);

$response = curl_exec($curl);
curl_close($curl);</code></pre>
                    </div>
                </div>

                <div class="response-title">Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Response</span>
                        <span class="badge-status">201 CREATED</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">{</span>
  <span class="property">"api_key"</span><span class="punctuation">:</span> <span class="string">"lit_key_9f3c1a2d5e6b7d8c90a1b2c3..."</span><span class="punctuation">,</span>
  <span class="property">"client"</span><span class="punctuation">:</span> <span class="punctuation">{</span>
    <span class="property">"id"</span><span class="punctuation">:</span> <span class="number">12</span><span class="punctuation">,</span>
    <span class="property">"name"</span><span class="punctuation">:</span> <span class="string">"Developer Demo Client"</span><span class="punctuation">,</span>
    <span class="property">"email"</span><span class="punctuation">:</span> <span class="string">"dev@example.com"</span><span class="punctuation">,</span>
    <span class="property">"is_active"</span><span class="punctuation">:</span> <span class="keyword">true</span><span class="punctuation">,</span>
    <span class="property">"subscription_required"</span><span class="punctuation">:</span> <span class="keyword">false</span><span class="punctuation">,</span>
    <span class="property">"subscription_expires_at"</span><span class="punctuation">:</span> <span class="keyword">null</span>
  <span class="punctuation">}</span>
<span class="punctuation">}</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: Regenerate Key -->
        <section id="regenerate-key" class="section">
            <div class="doc-column">
                <h2>Regenerate Key</h2>
                <p>Invalidate the active API key and issue a new one. The request must include the current valid API key in the headers. Once regenerated, the old key is immediately disabled.</p>
                
                <div class="url-box">
                    <span class="method-tag post">POST</span>
                    <span class="url-path">/v1/auth/regenerate-key</span>
                </div>

                <p>This endpoint requires authentication headers and does not take any request body parameters.</p>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Request Sample</span>
                        <div class="tabs">
                            <button class="tab-btn active" data-lang="curl" onclick="switchLanguage('curl')">cURL</button>
                            <button class="tab-btn" data-lang="js" onclick="switchLanguage('js')">JS</button>
                            <button class="tab-btn" data-lang="python" onclick="switchLanguage('python')">Python</button>
                            <button class="tab-btn" data-lang="php" onclick="switchLanguage('php')">PHP</button>
                        </div>
                    </div>
                    <div class="code-body">
                        <!-- cURL -->
                        <pre class="tab-content curl active"><code>curl -X POST <span class="string">"{{ url('/api/v1/auth/regenerate-key') }}"</span> \
  -H <span class="string">"X-API-Key: current_api_key_here"</span></code></pre>
                        <!-- JS -->
                        <pre class="tab-content js"><code>fetch(<span class="string">"{{ url('/api/v1/auth/regenerate-key') }}"</span>, {
  method: <span class="string">"POST"</span>,
  headers: {
    <span class="string">"X-API-Key"</span>: <span class="string">"current_api_key_here"</span>
  }
})
.then(res => res.json())
.then(data => console.log(data));</code></pre>
                        <!-- Python -->
                        <pre class="tab-content python"><code>import requests

url = <span class="string">"{{ url('/api/v1/auth/regenerate-key') }}"</span>
headers = {
    <span class="string">"X-API-Key"</span>: <span class="string">"current_api_key_here"</span>
}

response = requests.post(url, headers=headers)
print(response.json())</code></pre>
                        <!-- PHP -->
                        <pre class="tab-content php"><code>&lt;?php
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =&gt; <span class="string">"{{ url('/api/v1/auth/regenerate-key') }}"</span>,
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_POST =&gt; true,
  CURLOPT_HTTPHEADER =&gt; [<span class="string">"X-API-Key: current_api_key_here"</span>]
]);

$response = curl_exec($curl);
curl_close($curl);</code></pre>
                    </div>
                </div>

                <div class="response-title">Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Response</span>
                        <span class="badge-status">200 OK</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">{</span>
  <span class="property">"api_key"</span><span class="punctuation">:</span> <span class="string">"lit_key_new_api_key_xyz123..."</span><span class="punctuation">,</span>
  <span class="property">"message"</span><span class="punctuation">:</span> <span class="string">"API key regenerated successfully."</span>
<span class="punctuation">}</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: Site Config -->
        <section id="site-config" class="section">
            <div class="doc-column">
                <h2>Site Configuration</h2>
                <p>Retrieves the public site configuration settings. This configuration includes metadata such as the platform name, description, branding assets (logo url), active social media profiles, and whether maintenance mode is turned on.</p>
                
                <div class="url-box">
                    <span class="method-tag get">GET</span>
                    <span class="url-path">/v1/site-config</span>
                </div>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Request Sample</span>
                        <div class="tabs">
                            <button class="tab-btn active" data-lang="curl" onclick="switchLanguage('curl')">cURL</button>
                            <button class="tab-btn" data-lang="js" onclick="switchLanguage('js')">JS</button>
                            <button class="tab-btn" data-lang="python" onclick="switchLanguage('python')">Python</button>
                            <button class="tab-btn" data-lang="php" onclick="switchLanguage('php')">PHP</button>
                        </div>
                    </div>
                    <div class="code-body">
                        <!-- cURL -->
                        <pre class="tab-content curl active"><code>curl -X GET <span class="string">"{{ url('/api/v1/site-config') }}"</span> \
  -H <span class="string">"X-API-Key: your_api_key_here"</span></code></pre>
                        <!-- JS -->
                        <pre class="tab-content js"><code>fetch(<span class="string">"{{ url('/api/v1/site-config') }}"</span>, {
  method: <span class="string">"GET"</span>,
  headers: {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
  }
})
.then(res => res.json())
.then(data => console.log(data));</code></pre>
                        <!-- Python -->
                        <pre class="tab-content python"><code>import requests

url = <span class="string">"{{ url('/api/v1/site-config') }}"</span>
headers = {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
}

response = requests.get(url, headers=headers)
print(response.json())</code></pre>
                        <!-- PHP -->
                        <pre class="tab-content php"><code>&lt;?php
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =&gt; <span class="string">"{{ url('/api/v1/site-config') }}"</span>,
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_HTTPHEADER =&gt; [<span class="string">"X-API-Key: your_api_key_here"</span>]
]);

$response = curl_exec($curl);
curl_close($curl);</code></pre>
                    </div>
                </div>

                <div class="response-title">Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Response</span>
                        <span class="badge-status">200 OK</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">{</span>
  <span class="property">"site_name"</span><span class="punctuation">:</span> <span class="string">"Lit Lyrics"</span><span class="punctuation">,</span>
  <span class="property">"site_description"</span><span class="punctuation">:</span> <span class="string">"Liturgical lyrics database and mass selection generator"</span><span class="punctuation">,</span>
  <span class="property">"site_logo_url"</span><span class="punctuation">:</span> <span class="string">"{{ asset('storage/logo.png') }}"</span><span class="punctuation">,</span>
  <span class="property">"social"</span><span class="punctuation">:</span> <span class="punctuation">{</span>
    <span class="property">"facebook"</span><span class="punctuation">:</span> <span class="string">"https://facebook.com/litlyrics"</span><span class="punctuation">,</span>
    <span class="property">"twitter"</span><span class="punctuation">:</span> <span class="string">"https://twitter.com/litlyrics"</span><span class="punctuation">,</span>
    <span class="property">"instagram"</span><span class="punctuation">:</span> <span class="string">"https://instagram.com/litlyrics"</span>
  <span class="punctuation">}</span><span class="punctuation">,</span>
  <span class="property">"maintenance_mode"</span><span class="punctuation">:</span> <span class="keyword">false</span>
<span class="punctuation">}</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: Categories -->
        <section id="categories" class="section">
            <div class="doc-column">
                <h2>List Categories</h2>
                <p>Retrieves a listing of available song categories configured on the platform. These categories correspond to the liturgy sections (e.g. Entrance, Kyrie, Offertory, Communion, Recessional).</p>
                
                <div class="url-box">
                    <span class="method-tag get">GET</span>
                    <span class="url-path">/v1/categories</span>
                </div>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Request Sample</span>
                        <div class="tabs">
                            <button class="tab-btn active" data-lang="curl" onclick="switchLanguage('curl')">cURL</button>
                            <button class="tab-btn" data-lang="js" onclick="switchLanguage('js')">JS</button>
                            <button class="tab-btn" data-lang="python" onclick="switchLanguage('python')">Python</button>
                            <button class="tab-btn" data-lang="php" onclick="switchLanguage('php')">PHP</button>
                        </div>
                    </div>
                    <div class="code-body">
                        <!-- cURL -->
                        <pre class="tab-content curl active"><code>curl -X GET <span class="string">"{{ url('/api/v1/categories') }}"</span> \
  -H <span class="string">"X-API-Key: your_api_key_here"</span></code></pre>
                        <!-- JS -->
                        <pre class="tab-content js"><code>fetch(<span class="string">"{{ url('/api/v1/categories') }}"</span>, {
  method: <span class="string">"GET"</span>,
  headers: {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
  }
})
.then(res => res.json())
.then(data => console.log(data));</code></pre>
                        <!-- Python -->
                        <pre class="tab-content python"><code>import requests

url = <span class="string">"{{ url('/api/v1/categories') }}"</span>
headers = {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
}

response = requests.get(url, headers=headers)
print(response.json())</code></pre>
                        <!-- PHP -->
                        <pre class="tab-content php"><code>&lt;?php
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =&gt; <span class="string">"{{ url('/api/v1/categories') }}"</span>,
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_HTTPHEADER =&gt; [<span class="string">"X-API-Key: your_api_key_here"</span>]
]);

$response = curl_exec($curl);
curl_close($curl);</code></pre>
                    </div>
                </div>

                <div class="response-title">Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Response</span>
                        <span class="badge-status">200 OK</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">[</span>
  <span class="punctuation">{</span>
    <span class="property">"id"</span><span class="punctuation">:</span> <span class="number">1</span><span class="punctuation">,</span>
    <span class="property">"name"</span><span class="punctuation">:</span> <span class="string">"Entrance"</span>
  <span class="punctuation">}</span><span class="punctuation">,</span>
  <span class="punctuation">{</span>
    <span class="property">"id"</span><span class="punctuation">:</span> <span class="number">2</span><span class="punctuation">,</span>
    <span class="property">"name"</span><span class="punctuation">:</span> <span class="string">"Kyrie"</span>
  <span class="punctuation">}</span><span class="punctuation">,</span>
  <span class="punctuation">{</span>
    <span class="property">"id"</span><span class="punctuation">:</span> <span class="number">3</span><span class="punctuation">,</span>
    <span class="property">"name"</span><span class="punctuation">:</span> <span class="string">"Gloria"</span>
  <span class="punctuation">}</span>
<span class="punctuation">]</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: List Songs -->
        <section id="list-songs" class="section">
            <div class="doc-column">
                <h2>List Songs (Paginated)</h2>
                <p>Retrieves a paginated list of songs and lyrics. You can filter the results by search query or category, and control the page offset size.</p>
                
                <div class="url-box">
                    <span class="method-tag get">GET</span>
                    <span class="url-path">/v1/songs</span>
                </div>

                <div class="params-title">Query Parameters</div>
                <div class="params-list">
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">q</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">string</span>
                        </div>
                        <div class="param-desc-cell">Search string. Matches against song title or author.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">category</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">string</span>
                        </div>
                        <div class="param-desc-cell">Filter by specific category name.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">per_page</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">integer</span>
                        </div>
                        <div class="param-desc-cell">Number of items to return per page. Min: 1, Max: 100, Default: 20.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">page</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">integer</span>
                        </div>
                        <div class="param-desc-cell">The page index page (1-based index).</div>
                    </div>
                </div>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Request Sample</span>
                        <div class="tabs">
                            <button class="tab-btn active" data-lang="curl" onclick="switchLanguage('curl')">cURL</button>
                            <button class="tab-btn" data-lang="js" onclick="switchLanguage('js')">JS</button>
                            <button class="tab-btn" data-lang="python" onclick="switchLanguage('python')">Python</button>
                            <button class="tab-btn" data-lang="php" onclick="switchLanguage('php')">PHP</button>
                        </div>
                    </div>
                    <div class="code-body">
                        <!-- cURL -->
                        <pre class="tab-content curl active"><code>curl -X GET <span class="string">"{{ url('/api/v1/songs?category=Entrance&per_page=1') }}"</span> \
  -H <span class="string">"X-API-Key: your_api_key_here"</span></code></pre>
                        <!-- JS -->
                        <pre class="tab-content js"><code>fetch(<span class="string">"{{ url('/api/v1/songs?category=Entrance&per_page=1') }}"</span>, {
  method: <span class="string">"GET"</span>,
  headers: {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
  }
})
.then(res => res.json())
.then(data => console.log(data));</code></pre>
                        <!-- Python -->
                        <pre class="tab-content python"><code>import requests

url = <span class="string">"{{ url('/api/v1/songs') }}"</span>
params = {
    <span class="string">"category"</span>: <span class="string">"Entrance"</span>,
    <span class="string">"per_page"</span>: 1
}
headers = {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
}

response = requests.get(url, params=params, headers=headers)
print(response.json())</code></pre>
                        <!-- PHP -->
                        <pre class="tab-content php"><code>&lt;?php
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =&gt; <span class="string">"{{ url('/api/v1/songs?category=Entrance&per_page=1') }}"</span>,
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_HTTPHEADER =&gt; [<span class="string">"X-API-Key: your_api_key_here"</span>]
]);

$response = curl_exec($curl);
curl_close($curl);</code></pre>
                    </div>
                </div>

                <div class="response-title">Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Response</span>
                        <span class="badge-status">200 OK</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">{</span>
  <span class="property">"current_page"</span><span class="punctuation">:</span> <span class="number">1</span><span class="punctuation">,</span>
  <span class="property">"data"</span><span class="punctuation">:</span> <span class="punctuation">[</span>
    <span class="punctuation">{</span>
      <span class="property">"id"</span><span class="punctuation">:</span> <span class="number">1</span><span class="punctuation">,</span>
      <span class="property">"title"</span><span class="punctuation">:</span> <span class="string">"All The Earth (Psalm 100)"</span><span class="punctuation">,</span>
      <span class="property">"author"</span><span class="punctuation">:</span> <span class="string">"Traditional"</span><span class="punctuation">,</span>
      <span class="property">"category"</span><span class="punctuation">:</span> <span class="string">"Entrance"</span><span class="punctuation">,</span>
      <span class="property">"verses_html"</span><span class="punctuation">:</span> <span class="string">"&lt;p&gt;&lt;strong&gt;Chorus:&lt;/strong&gt;&lt;br&gt;All the earth proclaim...&lt;/p&gt;"</span><span class="punctuation">,</span>
      <span class="property">"verses_text"</span><span class="punctuation">:</span> <span class="string">"Chorus:\nAll the earth proclaim the Lord\nSing your praise to God..."</span><span class="punctuation">,</span>
      <span class="property">"music_sheet_path"</span><span class="punctuation">:</span> <span class="string">"music_sheets/all_the_earth.pdf"</span><span class="punctuation">,</span>
      <span class="property">"music_sheet_url"</span><span class="punctuation">:</span> <span class="string">"{{ asset('storage/music_sheets/all_the_earth.pdf') }}"</span><span class="punctuation">,</span>
      <span class="property">"created_at"</span><span class="punctuation">:</span> <span class="string">"2026-08-14T09:00:00.000000Z"</span><span class="punctuation">,</span>
      <span class="property">"updated_at"</span><span class="punctuation">:</span> <span class="string">"2026-08-14T09:00:00.000000Z"</span>
    <span class="punctuation">}</span>
  <span class="punctuation">]</span><span class="punctuation">,</span>
  <span class="property">"first_page_url"</span><span class="punctuation">:</span> <span class="string">"{{ url('/api/v1/songs?page=1') }}"</span><span class="punctuation">,</span>
  <span class="property">"last_page"</span><span class="punctuation">:</span> <span class="number">10</span><span class="punctuation">,</span>
  <span class="property">"next_page_url"</span><span class="punctuation">:</span> <span class="string">"{{ url('/api/v1/songs?page=2') }}"</span><span class="punctuation">,</span>
  <span class="property">"per_page"</span><span class="punctuation">:</span> <span class="number">1</span><span class="punctuation">,</span>
  <span class="property">"total"</span><span class="punctuation">:</span> <span class="number">10</span>
<span class="punctuation">}</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: Search Songs -->
        <section id="search-songs" class="section">
            <div class="doc-column">
                <h2>Search Songs (Lightweight)</h2>
                <p>Perform a lightweight and optimized autocomplete search. The payload returned excludes heavy verses fields to save bandwidth and is ideal for quick dropdown queries.</p>
                
                <div class="url-box">
                    <span class="method-tag get">GET</span>
                    <span class="url-path">/v1/songs/search</span>
                </div>

                <div class="params-title">Query Parameters</div>
                <div class="params-list">
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">q</span>
                            <span class="required-badge">required</span>
                            <span class="param-type">string</span>
                        </div>
                        <div class="param-desc-cell">The search query (minimum 2 characters). Matches song titles and authors.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">limit</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">integer</span>
                        </div>
                        <div class="param-desc-cell">The maximum results to return. Min: 1, Max: 50, Default: 10.</div>
                    </div>
                </div>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Request Sample</span>
                        <div class="tabs">
                            <button class="tab-btn active" data-lang="curl" onclick="switchLanguage('curl')">cURL</button>
                            <button class="tab-btn" data-lang="js" onclick="switchLanguage('js')">JS</button>
                            <button class="tab-btn" data-lang="python" onclick="switchLanguage('python')">Python</button>
                            <button class="tab-btn" data-lang="php" onclick="switchLanguage('php')">PHP</button>
                        </div>
                    </div>
                    <div class="code-body">
                        <!-- cURL -->
                        <pre class="tab-content curl active"><code>curl -X GET <span class="string">"{{ url('/api/v1/songs/search?q=Earth&limit=5') }}"</span> \
  -H <span class="string">"X-API-Key: your_api_key_here"</span></code></pre>
                        <!-- JS -->
                        <pre class="tab-content js"><code>fetch(<span class="string">"{{ url('/api/v1/songs/search?q=Earth&limit=5') }}"</span>, {
  method: <span class="string">"GET"</span>,
  headers: {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
  }
})
.then(res => res.json())
.then(data => console.log(data));</code></pre>
                        <!-- Python -->
                        <pre class="tab-content python"><code>import requests

url = <span class="string">"{{ url('/api/v1/songs/search') }}"</span>
params = {
    <span class="string">"q"</span>: <span class="string">"Earth"</span>,
    <span class="string">"limit"</span>: 5
}
headers = {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
}

response = requests.get(url, params=params, headers=headers)
print(response.json())</code></pre>
                        <!-- PHP -->
                        <pre class="tab-content php"><code>&lt;?php
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =&gt; <span class="string">"{{ url('/api/v1/songs/search?q=Earth&limit=5') }}"</span>,
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_HTTPHEADER =&gt; [<span class="string">"X-API-Key: your_api_key_here"</span>]
]);

$response = curl_exec($curl);
curl_close($curl);</code></pre>
                    </div>
                </div>

                <div class="response-title">Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Response</span>
                        <span class="badge-status">200 OK</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">[</span>
  <span class="punctuation">{</span>
    <span class="property">"id"</span><span class="punctuation">:</span> <span class="number">1</span><span class="punctuation">,</span>
    <span class="property">"title"</span><span class="punctuation">:</span> <span class="string">"All The Earth (Psalm 100)"</span><span class="punctuation">,</span>
    <span class="property">"author"</span><span class="punctuation">:</span> <span class="string">"Traditional"</span><span class="punctuation">,</span>
    <span class="property">"category"</span><span class="punctuation">:</span> <span class="string">"Entrance"</span><span class="punctuation">,</span>
    <span class="property">"music_sheet_url"</span><span class="punctuation">:</span> <span class="string">"{{ asset('storage/music_sheets/all_the_earth.pdf') }}"</span>
  <span class="punctuation">}</span>
<span class="punctuation">]</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: Get Song -->
        <section id="get-song" class="section">
            <div class="doc-column">
                <h2>Get Song by ID</h2>
                <p>Retrieves the complete data schema of a specific song, including formatted verses (html), plain text verses (raw txt), and sheets resources.</p>
                
                <div class="url-box">
                    <span class="method-tag get">GET</span>
                    <span class="url-path">/v1/songs/{id}</span>
                </div>

                <div class="params-title">Path Parameters</div>
                <div class="params-list">
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">id</span>
                            <span class="required-badge">required</span>
                            <span class="param-type">integer</span>
                        </div>
                        <div class="param-desc-cell">The unique identifier of the song.</div>
                    </div>
                </div>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Request Sample</span>
                        <div class="tabs">
                            <button class="tab-btn active" data-lang="curl" onclick="switchLanguage('curl')">cURL</button>
                            <button class="tab-btn" data-lang="js" onclick="switchLanguage('js')">JS</button>
                            <button class="tab-btn" data-lang="python" onclick="switchLanguage('python')">Python</button>
                            <button class="tab-btn" data-lang="php" onclick="switchLanguage('php')">PHP</button>
                        </div>
                    </div>
                    <div class="code-body">
                        <!-- cURL -->
                        <pre class="tab-content curl active"><code>curl -X GET <span class="string">"{{ url('/api/v1/songs/1') }}"</span> \
  -H <span class="string">"X-API-Key: your_api_key_here"</span></code></pre>
                        <!-- JS -->
                        <pre class="tab-content js"><code>fetch(<span class="string">"{{ url('/api/v1/songs/1') }}"</span>, {
  method: <span class="string">"GET"</span>,
  headers: {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
  }
})
.then(res => res.json())
.then(data => console.log(data));</code></pre>
                        <!-- Python -->
                        <pre class="tab-content python"><code>import requests

url = <span class="string">"{{ url('/api/v1/songs/1') }}"</span>
headers = {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
}

response = requests.get(url, headers=headers)
print(response.json())</code></pre>
                        <!-- PHP -->
                        <pre class="tab-content php"><code>&lt;?php
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =&gt; <span class="string">"{{ url('/api/v1/songs/1') }}"</span>,
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_HTTPHEADER =&gt; [<span class="string">"X-API-Key: your_api_key_here"</span>]
]);

$response = curl_exec($curl);
curl_close($curl);</code></pre>
                    </div>
                </div>

                <div class="response-title">Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Response</span>
                        <span class="badge-status">200 OK</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">{</span>
  <span class="property">"id"</span><span class="punctuation">:</span> <span class="number">1</span><span class="punctuation">,</span>
  <span class="property">"title"</span><span class="punctuation">:</span> <span class="string">"All The Earth (Psalm 100)"</span><span class="punctuation">,</span>
  <span class="property">"author"</span><span class="punctuation">:</span> <span class="string">"Traditional"</span><span class="punctuation">,</span>
  <span class="property">"category"</span><span class="punctuation">:</span> <span class="string">"Entrance"</span><span class="punctuation">,</span>
  <span class="property">"verses_html"</span><span class="punctuation">:</span> <span class="string">"&lt;p&gt;&lt;strong&gt;Chorus:&lt;/strong&gt;&lt;br&gt;All the earth proclaim...&lt;/p&gt;"</span><span class="punctuation">,</span>
  <span class="property">"verses_text"</span><span class="punctuation">:</span> <span class="string">"Chorus:\nAll the earth proclaim the Lord\nSing your praise to God..."</span><span class="punctuation">,</span>
  <span class="property">"music_sheet_path"</span><span class="punctuation">:</span> <span class="string">"music_sheets/all_the_earth.pdf"</span><span class="punctuation">,</span>
  <span class="property">"music_sheet_url"</span><span class="punctuation">:</span> <span class="string">"{{ asset('storage/music_sheets/all_the_earth.pdf') }}"</span><span class="punctuation">,</span>
  <span class="property">"created_at"</span><span class="punctuation">:</span> <span class="string">"2026-08-14T09:00:00.000000Z"</span><span class="punctuation">,</span>
  <span class="property">"updated_at"</span><span class="punctuation">:</span> <span class="string">"2026-08-14T09:00:00.000000Z"</span>
<span class="punctuation">}</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: Mass Selection -->
        <section id="mass-selection" class="section">
            <div class="doc-column">
                <h2>Generate Mass Selection</h2>
                <p>Generates a formatted, single plain text sheet mapping songs to specific liturgical segments of the Mass service (e.g. Entrance, Gloria, Communion, etc.). Excellent for generating pamphlets or handouts.</p>
                
                <div class="url-box">
                    <span class="method-tag post">POST</span>
                    <span class="url-path">/v1/mass-selection</span>
                </div>

                <div class="params-title">Query Parameters</div>
                <div class="params-list">
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">download</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">string</span>
                        </div>
                        <div class="param-desc-cell">If set to <code>"1"</code>, returns a raw <code>.txt</code> file attachment with a <code>Content-Disposition</code> download header instead of a JSON response.</div>
                    </div>
                </div>

                <div class="params-title">Request Body Parameters</div>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Provide song IDs as arrays inside a JSON object corresponding to the following fields:</p>
                <div class="params-list">
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">entrance</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Entrance segment.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">kyrie</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Kyrie segment.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">gloria</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Gloria.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">credo</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Credo.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">offertory</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Offertory.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">consecration</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Consecration.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">sanctus</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Sanctus.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">agnus_dei</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Agnus Dei.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">communion</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Communion.</div>
                    </div>
                    <div class="param-item">
                        <div class="param-name-cell">
                            <span class="param-name">dismissal</span>
                            <span class="optional-badge">optional</span>
                            <span class="param-type">array [integer]</span>
                        </div>
                        <div class="param-desc-cell">Song ID(s) for the Dismissal Recessional.</div>
                    </div>
                </div>
            </div>
            <div class="code-column">
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">Request Sample</span>
                        <div class="tabs">
                            <button class="tab-btn active" data-lang="curl" onclick="switchLanguage('curl')">cURL</button>
                            <button class="tab-btn" data-lang="js" onclick="switchLanguage('js')">JS</button>
                            <button class="tab-btn" data-lang="python" onclick="switchLanguage('python')">Python</button>
                            <button class="tab-btn" data-lang="php" onclick="switchLanguage('php')">PHP</button>
                        </div>
                    </div>
                    <div class="code-body">
                        <!-- cURL -->
                        <pre class="tab-content curl active"><code>curl -X POST <span class="string">"{{ url('/api/v1/mass-selection') }}"</span> \
  -H <span class="string">"X-API-Key: your_api_key_here"</span> \
  -H <span class="string">"Content-Type: application/json"</span> \
  -d <span class="string">'{
    "entrance": [1],
    "communion": [5],
    "dismissal": [12]
  }'</span></code></pre>
                        <!-- JS -->
                        <pre class="tab-content js"><code>fetch(<span class="string">"{{ url('/api/v1/mass-selection') }}"</span>, {
  method: <span class="string">"POST"</span>,
  headers: {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>,
    <span class="string">"Content-Type"</span>: <span class="string">"application/json"</span>
  },
  body: JSON.stringify({
    entrance: [1],
    communion: [5],
    dismissal: [12]
  })
})
.then(res => res.json())
.then(data => console.log(data));</code></pre>
                        <!-- Python -->
                        <pre class="tab-content python"><code>import requests

url = <span class="string">"{{ url('/api/v1/mass-selection') }}"</span>
headers = {
    <span class="string">"X-API-Key"</span>: <span class="string">"your_api_key_here"</span>
}
payload = {
    <span class="string">"entrance"</span>: [1],
    <span class="string">"communion"</span>: [5],
    <span class="string">"dismissal"</span>: [12]
}

response = requests.post(url, json=payload, headers=headers)
print(response.json())</code></pre>
                        <!-- PHP -->
                        <pre class="tab-content php"><code>&lt;?php
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =&gt; <span class="string">"{{ url('/api/v1/mass-selection') }}"</span>,
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_POST =&gt; true,
  CURLOPT_HTTPHEADER =&gt; [
    <span class="string">"X-API-Key: your_api_key_here"</span>,
    <span class="string">"Content-Type: application/json"</span>
  ],
  CURLOPT_POSTFIELDS =&gt; json_encode([
    <span class="string">"entrance"</span> =&gt; [1],
    <span class="string">"communion"</span> =&gt; [5],
    <span class="string">"dismissal"</span> =&gt; [12]
  ])
]);

$response = curl_exec($curl);
curl_close($curl);</code></pre>
                    </div>
                </div>

                <div class="response-title">Response Payload</div>
                <div class="code-box">
                    <div class="code-header">
                        <span class="code-title">JSON Response</span>
                        <span class="badge-status">200 OK</span>
                    </div>
                    <div class="code-body">
                        <pre><code><span class="punctuation">{</span>
  <span class="property">"filename"</span><span class="punctuation">:</span> <span class="string">"mass_selection_2026-08-14_10-35.txt"</span><span class="punctuation">,</span>
  <span class="property">"content"</span><span class="punctuation">:</span> <span class="string">"SELECTION FOR MASS\n\nENTRANCE:\nAll The Earth...\n\nCOMMUNION:\nMake Me A Channel..."</span>
<span class="punctuation">}</span></code></pre>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script>
        // Interactive Language Tabs synchronizer
        function switchLanguage(lang) {
            // Update active status on all tab buttons for this language
            document.querySelectorAll('.tab-btn').forEach(btn => {
                if (btn.getAttribute('data-lang') === lang) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Update visible content on all code blocks
            document.querySelectorAll('.tab-content').forEach(content => {
                if (content.classList.contains(lang)) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        }

        // Active sidebar link tracker during scrolling
        window.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    const id = entry.target.getAttribute('id');
                    if (entry.intersectionRatio > 0.3) {
                        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                            if (link.getAttribute('href') === `#${id}`) {
                                link.classList.add('active');
                            } else {
                                link.classList.remove('active');
                            }
                        });
                    }
                });
            }, { threshold: [0.1, 0.3, 0.5] });

            // Track sections
            document.querySelectorAll('.section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
</body>
</html>
