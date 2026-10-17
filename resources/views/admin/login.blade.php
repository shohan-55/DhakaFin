<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Admin sign in | DhakaFin</title>
<style>body{margin:0;background:#071c20;color:#f4faf8;min-height:100vh;display:grid;place-items:center;font:16px/1.55 system-ui,sans-serif}main{width:min(400px,calc(100vw - 3rem));background:#102f32;padding:2rem;border:1px solid #27635a;border-radius:18px}h1{margin:.5rem 0 1.5rem}label{display:block;margin:.8rem 0 .25rem}input{display:block;width:100%;box-sizing:border-box;padding:.8rem;background:#fff;color:#10252a;border:0;border-radius:8px}button{margin-top:1.5rem;width:100%;border:0;border-radius:8px;padding:.9rem;background:#73ddc3;color:#042b27;font-weight:700;cursor:pointer}.muted{color:#b2c8c2}a{color:#73ddc3}.errors{color:#ffc9c9}</style></head>
<body><main><p class="muted">DHAKAFIN / SECURE ADMINISTRATION</p><h1>Administrator sign in</h1>
@if ($errors->any())<p class="errors" role="alert">Unable to sign in. Please verify your credentials and access rights.</p>@endif
<form method="post" action="{{ route('admin.login.submit') }}">@csrf
<label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
<label for="password">Password</label><input type="password" id="password" name="password" autocomplete="current-password" required>
<button type="submit">Sign in</button></form>
<p><a href="/">Return to DhakaFin</a></p></main></body></html>
