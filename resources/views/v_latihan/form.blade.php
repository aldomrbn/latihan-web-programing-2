<!DOCTYPE html>
<html>
<head>
    <title>Form Mahasiswa</title>
    <style>
        .card { width: 350px; margin: 30px auto; padding: 20px; border: 1px solid #333; font-family: sans-serif; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 6px; box-sizing: border-box; }
        button { padding: 6px 15px; background: #e0e0e0; border: 1px solid #999; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <form>
            <div class="form-group">
                <label>NIM</label>
                <input type="text" value="17245068">
            </div>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="Renaldo Marbun">
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <input type="text">
            </div>
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>