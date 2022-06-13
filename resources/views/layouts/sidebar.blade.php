      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="#">{{config('app.name')}}</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">BB</a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ (request()->routeIs('home')) ? 'active' : null }}">
              <a href="{{route('home')}}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            @role('Admin')
            @endrole
            <li class="{{ (request()->is('home/blog*')) ? 'nav-item dropdown active' : 'nav-item dropdown' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-blog"></i><span>Blog</span></a>
              <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('post.*') ? 'active' : null }}">
                  <a class="nav-link" href="{{route('post.index')}}"><i class="fa fa-newspaper"></i><span>Article</span></a>
                </li>
                <li class="{{ request()->routeIs('category.*') ? 'active' : null }}">
                  <a class="nav-link" href="{{route('category.index')}}"><i class="fa fa-code-branch"></i><span>Category</span></a>
                </li>
              </ul>
            </li>
          </ul>

          <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="https://api.whatsapp.com/send/?phone=6281513947715&text=Hi%20Bintang..." class="btn btn-primary btn-lg btn-block btn-icon-split" target="_blank">
              <i class="fa fa-phone"></i>Whatsapp Me
            </a>
          </div>
        </aside>
      </div>