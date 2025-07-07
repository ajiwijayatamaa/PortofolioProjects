<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
      <a href="https://www.creative-tim.com" class="simple-text logo-mini">
        <div class="logo-image-small">
          <img src="{{asset('templateadmin')}}/assets/img/logo-small.png">
        </div>
        <!-- <p>CT</p> -->
      </a>
      <a href="https://www.creative-tim.com" class="simple-text logo-normal">
        Admin
      </a>
    </div>
    <div class="sidebar-wrapper">
      <ul class="nav">
        <li>
          <a href="./dashboard.html">
            {{-- <i class="nc-icon nc-bank"></i> --}}
            <p>Dashboard</p>
          </a>
        </li>
        <li>
          <a href="{{route('products.index')}}">
            {{-- <i class="nc-icon nc-diamond"></i> --}}
            <p>Kelola Master Produk</p>
          </a>
        </li>
        <li>
          <a href="./map.html">
            {{-- <i class="nc-icon nc-pin-3"></i> --}}
            <p>Kelola Furniture Set</p>
          </a>
        </li>
        <li>
          <a href="./notifications.html">
            {{-- <i class="nc-icon nc-bell-55"></i> --}}
            <p>Kelola Custom Furniture</p>
          </a>
        </li>
        {{-- <li class="active ">
          <a href="./user.html">
            <i class="nc-icon nc-single-02"></i>
            <p>User Profile</p>
          </a>
        </li>
        <li>
          <a href="./tables.html">
            <i class="nc-icon nc-tile-56"></i>
            <p>Table List</p>
          </a>
        </li> --}}
        {{-- <li>
          <a href="./typography.html">
            <i class="nc-icon nc-caps-small"></i>
            <p>Typography</p>
          </a>
        </li> --}}
        {{-- <li class="active-pro">
          <a href="./upgrade.html">
            <i class="nc-icon nc-spaceship"></i>
            <p>Upgrade to PRO</p>
          </a>
        </li> --}}
      </ul>
    </div>
  </div>