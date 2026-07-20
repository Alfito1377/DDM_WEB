@extends('users.layouts.app')

@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Informasi Pengirim</div>
          <div class="card-body">
            <div class="mb-3">
              <label for="nama-pengirim" class="form-label">Nama Pengirim</label>
              <input type="text" class="form-control" id="nama-pengirim" name="nama_pengirim" required>
            </div>
            <div class="mb-3">
              <label for="nomor-handphone" class="form-label">Nomor Handphone</label>
              <input type="text" class="form-control" id="nomor-handphone" name="nomor_handphone" required>
            </div>
            <div class="mb-3">
              <button type="button" class="btn btn-primary" id="submit-button">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection