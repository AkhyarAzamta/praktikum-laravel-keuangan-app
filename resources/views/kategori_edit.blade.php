@extends ('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Edit Kategori
                        <a href="{{ url('/kategori') }}" class="float-right btn btn-sm btn-primary">Kembali</a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('/kategori/update/' . $kategori->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Nama Kategori</label>
                                <input type="text" autocomplete="off" name="kategori" class="form-control"
                                    value="{{ $kategori->kategori }}">
                                @if ($errors->has('kategori'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('kategori') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type ="submit" class="btn btn-primary" value="Simpan">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
