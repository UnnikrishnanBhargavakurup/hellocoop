<?php

namespace HelloCoop\Renderers;

class DefaultPageRenderer implements PageRendererInterface
{
    /**
     * Render an error page.
     */
    public function renderErrorPage(
        string $error,
        string $errorDescription,
        string $errorURI,
        string $targetURI = '/'
    ): string {
        return <<<HTML
<html lang="en-US">
    <head>
        <title>Hellō</title>
        <meta charset="UTF-8" />
        <style>
            body {
                color: #303030;
                font-family: sans-serif;
                text-align: center;
                padding: 0;
                margin: 0;
            }
            @media (prefers-color-scheme: dark) {
                body {
                    background-color: #151515;
                    color: #d4d4d4
                }
            }
            h1, p {
                font-size: 18px;
            }
            h1 {
                font-weight: 500;
            }
            p {
                font-weight: 100;
                line-height: 28px;
            }
            header {
                background-color: #303030;
                height: 48px;
                font-size: 20px;
                font-weight: 600;
                display: flex;
                justify-content: center;
                align-items: center;
                -webkit-font-smoothing: antialiased;
                color: #d4d4d4;
            }
            main {
                padding: 32px;
            }
            a {
                text-decoration: none;
            }
            a:not(.link-btn) {
                text-decoration: underline;
                color: inherit;
                font-weight: 100;
            }
            .link-btn {
                color: #d4d4d4;
                font-size: inherit;
                background-color: #303030;
                border-radius: 0.375rem;
                display: inline-block;
                padding: 12px 48px;
                border: 2px solid #808080;
                cursor: pointer;
                margin: 20px auto;
            }
        </style>
    </head>
    <body>
        <header>Hellō</header>
        <main>
        <h1>Error: <?= htmlspecialchars($error) ?></h1>
        <?php if (!empty($errorDescription)): ?>
            <p><?= htmlspecialchars($errorDescription) ?></p>
        <?php endif; ?>

        <?php if (!empty($errorURI)): ?>
            <a href="<?= htmlspecialchars($errorURI) ?>">Learn more</a><br/>
        <?php endif; ?>

        <?php if (!empty($targetURI)): ?>
            <a href="<?= htmlspecialchars($targetURI) ?>" class="link-btn">Continue</a>
        <?php endif; ?>
        </main>
    </body>
</html>
HTML;
    }

    /**
     * Render a same-site compliance page.
     *
     * @return string Rendered page content.
     */
    public function renderSameSitePage(): string
    {
        // XXX: Is this needed?
        // $info = $data['info'] ?? 'No additional information provided.';

        return <<<HTML
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8"><title>Loading ...</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="spinner"></div>
        <script>
            const currentURL=window.location.href;
            const newURL=new URL(currentURL);
            
            newURL.searchParams.set('same_site','true');
            fetch(newURL)
                .then(response=>response.json())
                .then(data=>{
                    if(data && data.target_uri)
                    {
                        window.location=data.target_uri
                    }
                    else
                    {
                        console.error("No target_uri found -> /");
                        window.location='/'
                    }
                })
                .catch(
                    error=>{
                        console.error("An error occurred:",error);
                        window.location='/'
                    });
        </script>
        <style>
            body {
                height:100%;
                min-width:320px;
                overflow-x:auto;
                overflow-y:hidden
            }
            body {
                font-family:sans-serif;
                display:flex;
                align-items:center;
                justify-content:center
            }
            .spinner {
                position:absolute;
                left:50%;
                top:50%;
                height:40px;
                width:40px;
                margin:-26px 0 0 -26px;
                box-sizing:content-box;
                animation:rotation 1s infinite linear;
                border-width:6px;
                border-style:solid;
                border-radius:100%
            }
            @keyframes rotation {
                from {
                    transform:rotate(0deg)
                }
                to {
                    transform:rotate(360deg)
                }
            }
            @media (prefers-color-scheme:dark) {
                body {
                    color:#d4d4d4;
                    background:#151515;
                    color-scheme:dark
                }
                .spinner {
                    border-color:rgba(116,116,116,0.3);
                    border-top-color:rgb(116,116,116)
                }
            }
            @media (prefers-color-scheme:light) {
                body {
                    color:#303030;
                    background:white;
                    color-scheme:light
                }
                .spinner {
                    border-color:rgba(75,75,75,0.3);
                    border-top-color:rgb(75,75,75)
                }
            }
        </style>
    </body>
    </html>
HTML;
    }

    /**
     * Render redirect URI bounce page.
     *
     * @return string Rendered page content.
     */
    public function renderRedirectURIBounce(): string
    {
        // XXX: Is this needed?
        // $info = $data['info'] ?? 'No additional information provided.';

        return <<<HTML
        <html lang="en-US">
            <head>
                <title></title>
                <script>
                    const baseURL = window.location.href.split("?")[0]
                    const searchParams = new URLSearchParams(window.location.search)
                    searchParams.set("redirect_uri", window.location.origin + window.location.pathname)
                    window.location.href = baseURL + '?' + searchParams.toString()
                </script>
            </head>
        </html>
HTML;
    }

    /**
     * Render wild card console.
     *
     * @return string Rendered page content.
     */
    public function renderWildcardConsole(string $uri, string $targetURI, string $appName, string $redirectURI): string
    {
        // XXX: Is this needed?
        // $info = $data['info'] ?? 'No additional information provided.';

        return <<<HTML
        <html lang="en-US">
            <head>
                <title></title>
                <meta charset="UTF-8" />
                <style>
                    body {
                        color: #303030;
                        font-family: sans-serif;
                        text-align: center;
                        padding: 0;
                        margin: 0;
                    }
                    @media (prefers-color-scheme: dark) {
                        body {
                            background-color: #151515;
                            color: #d4d4d4
                        }
                    }
                    h1, p {
                        font-size: 18px;
                    }
                    h1 {
                        font-weight: 100;
                    }
                    p {
                        font-weight: 500;
                        line-height: 28px;
                    }
                    header {
                        background-color: #303030;
                        height: 48px;
                        font-size: 20px;
                        font-weight: 600;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        -webkit-font-smoothing: antialiased;
                        color: #d4d4d4;
                    }
                    main {
                        padding: 32px;
                    }
                    a {
                        text-decoration: none;
                        color: inherit;
                        font-weight: 100;
                    }
                    a:hover, a:focus {
                        text-decoration: underline;
                    }
                    button {
                        color: #d4d4d4;
                        font-size: inherit;
                        background-color: #303030;
                        border-radius: 0.375rem;
                        display: inline-block;
                        padding: 12px 24px;
                        border: 2px solid #808080;
                        cursor: pointer;
                        margin-bottom: 20px;
                    }
                </style>
                <script>
                    function addToRedirectURI(){
                        window.open("${uri}", "_blank")
                        window.location.href = "${targetURI}"
                    }
                </script>
            </head>
            <body>
                <header>Hellō</header>
                <main>
                    <h1>The following Redirect URI is not configured for</h1>
                    <p>
                        <span>{$appName}</span><br/>
                        <span>{$redirectURI}</span><br/>
                    </p>
                    <button onClick="addToRedirectURI()">Add to Redirect URIs</button><br/>
                    <a href="{$targetURI}">Do this later</a>
                </main>
            </body>
        </html>
HTML;
    }
}
