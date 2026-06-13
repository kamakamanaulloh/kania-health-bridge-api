# Authentication

Semua endpoint selain `/api/v1/health` wajib menggunakan header:

```http
X-BRIDGE-KEY: your-secret-key
Accept: application/json
Content-Type: application/json
```

Key diatur di `.env`:

```env
BRIDGE_KEY=your-secret-key
```

Untuk membatasi IP SIMRS:

```env
BRIDGE_ALLOWED_IPS=192.168.1.10,10.10.10.2
```
