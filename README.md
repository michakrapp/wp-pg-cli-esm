# wp-pg-cli-esm

Test plugin to demonstrate the wp-playground-cli bug with ES modules.

## Bug Description

The `@wp-playground/cli` (v3.1.2) does not set `Content-Type` HTTP headers when serving static files from VFS mounts. This breaks ES module loading in browsers.

### Error Message

```
Failed to load module script: Expected a JavaScript-or-Wasm module script but the server responded with a MIME type of "". Strict MIME type checking is enforced for module scripts per HTML spec.
```

### Root Cause

When requesting a `.js` file from a mounted directory, the server returns:

```
HTTP/1.1 200 OK
X-Powered-By: Express
Date: Sat, 28 Feb 2026 01:42:29 GMT
Connection: keep-alive
Keep-Alive: timeout=5
```

**No Content-Type header!** Without it, browsers refuse to load ES modules.

## How to Reproduce

1. Start the playground with this plugin:
   ```bash
   cd /path/to/wp-pg-cli-esm
   npx @wp-playground/cli server --auto-mount --blueprint ./blueprint.json
   ```

2. Test the MIME type:
   ```bash
   curl -I http://127.0.0.1:9400/wp-content/plugins/wp-pg-cli-esm/view.js
   ```

3. Observe: No Content-Type header in response

## Files

- `block.json` - Block definition with `viewScriptModule`
- `view.js` - Frontend ES module script
- `index.js` - Editor script
- `plugin.php` - Plugin registration
- `blueprint.json` - Creates test page with block

## Expected Fix

The server should return `Content-Type: application/javascript` for `.js` files.
