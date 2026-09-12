<?php include 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna - Sistem Pakar</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { height: 100vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); }
        
        .register-box { width: 90%; max-width: 450px; background: #fff; padding: 40px 30px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,.3); text-align: center; position: relative; overflow: hidden; }
        .register-box::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: linear-gradient(to right, #2a5298, #1e3c72); }
        h2 { color: #1e3c72; font-weight: 600; font-size: 24px; margin-bottom: 25px; }
        
        .input-group { position: relative; margin-bottom: 20px; text-align: left; }
        .icon-left, .toggle-password { position: absolute; top: 50%; transform: translateY(-50%); color: #aaa; transition: .3s; }
        .icon-left { left: 15px; }
        .toggle-password { right: 15px; cursor: pointer; }
        .toggle-password:hover, .login-link a:hover { color: #1e3c72; }
        
        .input-group input { width: 100%; padding: 14px 15px 14px 45px; border: 2px solid #eee; border-radius: 10px; font-size: 14px; background: #fcfcfc; transition: .3s; }
        .input-group input:focus { outline: none; border-color: #2a5298; background: #fff; box-shadow: 0 0 0 4px rgba(42,82,152,.1); }
        .input-group input:focus + .icon-left { color: #2a5298; }
        
        .btn { width: 100%; padding: 14px; background: linear-gradient(135deg, #2a5298, #1e3c72); border: none; color: #fff; font-size: 15px; font-weight: 500; border-radius: 10px; cursor: pointer; transition: .3s; box-shadow: 0 4px 15px rgba(42,82,152,.3); margin-top: 10px; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(42,82,152,.4); }
        .btn i { margin-right: 5px; }
        
        .login-link, .footer { margin-top: 25px; font-size: 14px; color: #666; }
        .login-link a { text-decoration: none; color: #2a5298; font-weight: 600; transition: .3s; }
        .login-link a:hover { text-decoration: underline; }
        .footer { font-size: 12px; color: #bbb; }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Registrasi Pengguna</h2>

    <form action="controllers/RegisterController.php" method="POST">
        <div class="input-group">
            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
            <i class="fas fa-user icon-left"></i>
        </div>
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
            <i class="fas fa-id-card icon-left"></i>
        </div>
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i class="fas fa-lock icon-left"></i>
            <i class="fas fa-eye toggle-password"></i>
        </div>
        <div class="input-group">
            <input type="password" name="konfirmasi" id="konfirmasi" placeholder="Konfirmasi Password" required>
            <i class="fas fa-lock icon-left"></i>
            <i class="fas fa-eye toggle-password"></i>
        </div>
        <button type="submit" name="register" class="btn"><i class="fas fa-user-plus"></i> Daftar</button>
    </form>

    <div class="login-link">Sudah punya akun? <a href="index.php">Login Disini</a></div>
    <div class="footer">PT Simetri Indonesia &copy; 2026</div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.onclick = e => {
            let inp = e.target.parentElement.querySelector('input');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            e.target.classList.toggle('fa-eye-slash');
        };
    });
</script>

</body>
</html>