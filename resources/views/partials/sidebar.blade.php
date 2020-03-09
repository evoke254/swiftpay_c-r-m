  <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
  </a>
  <nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
      <div class="sidebar-brand">
        <a href="#">Business Softwares</a>
        <div id="close-sidebar">
          <i class="fas fa-times"></i>
        </div>
      </div>
      <div class="sidebar-header">
        <div class="user-pic">
          <img class="float-left img-responsive rounded"  src="{{ asset('images/logo.png') }}">
        </div>
        <div class="user-info">
          <span class="user-name font-weight-bold"> {{ Auth::user()->name ?? 'User'}}</span>
          <span class="user-role">Administrator</span>
          <span class="user-status">
            <i class="fa fa-circle"></i>
            <span>Online</span>
          </span>
        </div>
      </div>
      <!-- sidebar-header  -->
      <div class="sidebar-search">
        <div>
          <div class="input-group">
            <input type="text" class="form-control search-menu" placeholder="Search...">
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fa fa-search" aria-hidden="true"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
      <!-- sidebar-search  -->
      <div class="sidebar-menu">
        <ul>
          <li class="header-menu">
            <span>General</span>
          </li>
          <li>
            <a href="/">
              <i class="fa fa-tachometer-alt"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-ship" aria-hidden="true"></i>
              <span>Inventory</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="/products">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span>Products</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-chart-line"></i>
              <span>Sales</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="/opportunities">
                    <i class="far fa-gem"></i>
                    <span>Opportunities</span>
                  </a>
                </li>
                <li>
                  <a href="/quotes">
                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                    <span>Quotes</span>
                  </a>
                </li>
                <li>
                  <a href="/invoices">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <span>Invoices</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-address-card" aria-hidden="true"></i>
              <span>Clients</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="/contacts">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Individuals</span>
                  </a>
                </li>
                <li>
                  <a href="/organizations">
                    <i class="fa fa-university" aria-hidden="true"></i>
                    <span>Organizations</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-bolt" aria-hidden="true"></i>
              <span>POS</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="/pointofsales">
                    <i class="fas fa-cash-register"></i>
                    <span>Cash Register</span>
                  </a>
                </li>
                <li>
                  <a href="/pendingbills">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Pending Bills</span>
                  </a>
                </li>
                <li>
                  <a href="/clearedbills">
                    <i class="fas fa-check-circle"></i>
                    <span>Cleared Bills</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fas fa-calculator"></i>
              <span>Accounting</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="/transactions">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transactions</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fas fa-user-clock"></i>
              <span>HRM</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="/employees">
                    <i class="fas fa-users-cog"></i>
                    <span>Employees</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="header-menu">
            <span>Extra</span>
          </li>
          <li>
            <a href="/admin">
              <i class="fa fa-cogs" aria-hidden="true"></i>
              <span>Settings</span>
              <span class="badge badge-pill badge-warning">Admin</span>
            </a>
          </li>
        </ul>
      </div>
      <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    <div class="sidebar-footer">
      <a href="#">
        <i class="fa fa-bell"></i>
        <span class="badge badge-pill badge-warning notification">3</span>
      </a>
      <a href="#">
        <i class="fa fa-envelope"></i>
        <span class="badge badge-pill badge-success notification">7</span>
      </a>
      <a href="#">
        <i class="fa fa-cog"></i>
        <span class="badge-sonar"></span>
      </a>
      <a href="#">
        <i class="fa fa-power-off"></i>
      </a>
    </div>
  </nav>