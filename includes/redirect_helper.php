<?php
/**
 * Dynamic redirect function that handles extensionless URLs
 * @param string $url The URL to redirect to (without extension)
 * @param int $status_code HTTP status code (default: 302)
 */
function redirect($url, $status_code = 302) {
    // Remove any existing extensions
    $url = preg_replace('/\.(php|html)$/', '', $url);
    
    // Add anchor if present in current URL
    $anchor = '';
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '#') !== false) {
        $anchor = parse_url($_SERVER['REQUEST_URI'], PHP_URL_FRAGMENT);
        if ($anchor) {
            $anchor = '#' . $anchor;
        }
    }
    
    header("Location: $url$anchor", true, $status_code);
    exit();
}

/**
 * Get the current base URL without extension
 */
function getCurrentUrl() {
    $url = $_SERVER['REQUEST_URI'];
    // Remove extension and query string
    $url = preg_replace('/\.(php|html)(\?.*)?$/', '$2', $url);
    return $url;
}

/**
 * Generate extensionless URL for links
 */
function url($path) {
    return preg_replace('/\.(php|html)$/', '', $path);
}
?>