@extends('layouts.app')

@section('title', 'Data Profil')

@section('contents')
  <div class="row">
    <div class="col-lg-10 mb-10">
    
      <!-- Foto Profile -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2 flex">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <img class="img-profile rounded-circle" src="{{ asset('img/foto_profile.jpeg') }}" height="150">
            </div>
            <div class="col-auto">
            </div>
          </div>
        </div>
      </div>
    </div>

  <!-- Content Row -->
  <div class="row">

    <!-- Content Column -->
    <div class="col-lg-6 mb-4">

      <!-- Nama Kandidat -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h10 class="m-0 text-primary">Nama Kandidat</h10>
        </div>
        <div class="card-body">
          <h8 class="m-0 font-weight-bold text-primary">Rickardo Aprianto</h8>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">

      <!-- Posisi Kandidat -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h10 class="m-0 text-primary">Posisi Kandidat</h10>
        </div>
        <div class="card-body">
          <h8 class="m-0 font-weight-bold text-primary">Website Programmer</h8> 
        </div>
      </div>
    </div>
  </div>

<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
        });
    </script>
    
@endif

@endsection
