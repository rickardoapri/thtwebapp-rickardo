<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center">
    <div class="sidebar-brand-icon">
      <img src="{{ asset('img/white-shopping-bag.png') }}" width="30">
    </div>
    <div class="sidebar-brand-text mx-1">SIMS Web App</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('barang') }}">
      <img src="{{ asset('img/Package.png') }}" class="w-3 h-3 mr-2" alt="Produk">
      <span>Produk</span></a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <img src="{{ asset('img/User.png') }}" class="w-3 h-3 mr-2" alt="Profil">
      <span>Profil</span></a>
  </li>

  @if (auth()->user()->level == 'Admin')
  <li class="nav-item">
    <a class="nav-link" href="{{ route('logout') }}">
      <img src="{{ asset('img/SignOut.png') }}" class="w-3 h-3 mr-2" alt="Profil">
      <span>Logout</span></a>
  </li>
	@endif

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
