<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ganti Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Ganti Password</h2>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/ganti_password/aksi') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label>Masukan Password Lama</label>
                <input type="password" placeholder="********" name="current-password" class="form-control" required>
                @if ($errors->has('current-password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('current-password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label>Masukan Password Baru</label>
                <input type="password" placeholder="********" name="new-password" class="form-control" required>
                @if ($errors->has('new-password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('new-password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" placeholder="********" name="new-password_confirmation" class="form-control" required>
                @if ($errors->has('new-password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('new-password_confirmation') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Ganti Password</button>
        </form>
    </div>
</body>

</html>
