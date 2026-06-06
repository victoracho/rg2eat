<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login · RG2 Eat CMS</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root { --coral:#FF8C5A; --cream:#FEF4EC; --coral-dark:#E66B3A; --coral-light:#FFB89A; --coral-pale:#FFF0E8; }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'DM Sans',sans-serif; background:var(--cream); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem; }
  .card { background:#fff; border:1.5px solid var(--coral-light); border-radius:8px; padding:2.5rem; width:100%; max-width:380px; box-shadow:0 18px 50px rgba(255,140,90,.18); }
  .card h1 { font-family:'Playfair Display',serif; font-size:1.8rem; margin-bottom:.4rem; }
  .card .sub { color:#666; font-size:.9rem; margin-bottom:2rem; }
  label { display:block; font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; font-weight:500; color:#333; margin-bottom:.4rem; }
  input[type=email], input[type=password] {
    width:100%; padding:.7rem .9rem; border:1.5px solid #ddd; border-radius:3px; font-size:.95rem;
    font-family:inherit; margin-bottom:1.1rem; transition:border-color .15s;
  }
  input:focus { outline:none; border-color:var(--coral); }
  button {
    width:100%; padding:.85rem; background:var(--coral); color:#fff; border:none; cursor:pointer;
    text-transform:uppercase; letter-spacing:.1em; font-size:.85rem; font-weight:500; border-radius:3px;
    transition:background .2s;
  }
  button:hover { background:var(--coral-dark); }
  .err { background:#fde8e3; color:#7a1f1f; padding:.6rem .8rem; border-radius:3px; font-size:.82rem; margin-bottom:1rem; }
  .check { display:flex; align-items:center; gap:.5rem; margin-bottom:1.2rem; font-size:.85rem; color:#444; }
  .back { display:block; text-align:center; margin-top:1.4rem; font-size:.82rem; color:#888; text-decoration:none; }
  .back:hover { color:var(--coral); }
</style>
</head>
<body>
<form class="card" method="POST" action="{{ route('login.attempt') }}">
  @csrf
  <h1>Hola de nuevo 👋</h1>
  <p class="sub">Entra al panel del CMS de RG2 Eat.</p>

  @if($errors->any())
    <div class="err">{{ $errors->first() }}</div>
  @endif

  <label for="email">Email</label>
  <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus required>

  <label for="password">Contraseña</label>
  <input id="password" type="password" name="password" required>

  <label class="check">
    <input type="checkbox" name="remember" value="1" style="width:auto;margin:0;">
    Recuérdame en este equipo
  </label>

  <button type="submit">Entrar</button>
  <a href="{{ route('home') }}" class="back">← Volver al sitio</a>
</form>
</body>
</html>
