<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Pakar</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { height: 100vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); }
        
        .login-box { width: 90%; max-width: 400px; background: #fff; padding: 40px 30px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,.3); text-align: center; position: relative; overflow: hidden; }
        .login-box::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: linear-gradient(to right, #2a5298, #1e3c72); }
        
        .logo { width: 90px; margin-bottom: 10px; transition: .3s; }
        .logo:hover { transform: scale(1.05); }
        .subtitle { color: #888; font-size: 13px; margin-bottom: 30px; }
        
        .input-group { position: relative; margin-bottom: 20px; text-align: left; }
        .icon-left, .toggle-password { position: absolute; top: 50%; transform: translateY(-50%); color: #aaa; transition: .3s; }
        .icon-left { left: 15px; }
        .toggle-password { right: 15px; cursor: pointer; }
        .toggle-password:hover, .register a:hover { color: #1e3c72; }
        
        .input-group input { width: 100%; padding: 14px 15px 14px 45px; border: 2px solid #eee; border-radius: 10px; font-size: 14px; background: #fcfcfc; transition: .3s; }
        .input-group input:focus { outline: none; border-color: #2a5298; background: #fff; box-shadow: 0 0 0 4px rgba(42,82,152,.1); }
        .input-group input:focus + .icon-left { color: #2a5298; }
        
        .btn-login { width: 100%; padding: 14px; background: linear-gradient(135deg, #2a5298, #1e3c72); border: none; color: #fff; font-size: 15px; font-weight: 500; border-radius: 10px; cursor: pointer; transition: .3s; box-shadow: 0 4px 15px rgba(42,82,152,.3); margin-top: 10px; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(42,82,152,.4); }
        .btn-login i { margin-right: 5px; }
        
        .register, .footer { margin-top: 25px; font-size: 14px; color: #666; }
        .register a { text-decoration: none; color: #2a5298; font-weight: 600; transition: .3s; }
        .register a:hover { text-decoration: underline; }
        .footer { font-size: 12px; color: #bbb; }
    </style>
</head>
<body>

<div class="login-box">
    <img src="assets/img/logo.png" class="logo" alt="Logo">
    <div class="subtitle">Diagnosa Kerusakan Hardware Komputer</div>

    <form action="controllers/LoginController.php" method="POST">
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
            <i class="fas fa-user icon-left"></i>
        </div>
        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i class="fas fa-lock icon-left"></i>
            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>
        <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Login</button>
    </form>

    <div class="register">Belum memiliki akun? <a href="register.php">Daftar Disini</a></div>
    <div class="footer">PT Simetri Indonesia &copy; 2026</div>
</div>

<script>
    const p = document.querySelector('#password');
    document.querySelector('#togglePassword').onclick = e => {
        p.type = p.type === 'password' ? 'text' : 'password';
        e.target.classList.toggle('fa-eye-slash');
    };
</script>

</body>
</html>