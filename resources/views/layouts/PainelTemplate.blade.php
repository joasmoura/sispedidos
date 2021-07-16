<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="BASE" content="{{env('APP_URL')}}">

        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="../assets/img/favicon.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>@yield('titulo',(isset($titulo) ? $titulo : 'Painel SisPedidos'))</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

        <!-- CSS Files -->
        <link href="{{asset('assets/plugins/js/font-awesome/css/all.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/css/bootstrap.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/css/now-ui-dashboard.css?v=1.5.0')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/js/select2/css/select2.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/js/datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/css/painel.css')}}" rel="stylesheet" />
    </head>

    <body class="">
        <div class="wrapper ">
            <div class="sidebar" data-color="orange">
              <!--
                Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
            -->
              <div class="logo">
                <a href="{{route('painel.index')}}" class="simple-text logo-mini">
                  SIS
                </a>
                <a href="{{route('painel.index')}}" class="simple-text logo-normal">
                  Pedidos
                </a>
              </div>

              <div class="sidebar-wrapper ps" id="sidebar-wrapper">
                <ul class="nav">

                  <li class="{{(url()->current() == route('painel.index') ? 'active' : '')}}">
                    <a href="{{route('painel.index')}}">
                        <i class="fas fa-tachometer-alt"></i>
                      <p>Painel</p>
                    </a>
                  </li>

                  <li class="{{(url()->current() == route('painel.negocios.index') ? 'active' : '')}}">
                    <a href="{{route('painel.negocios.index')}}">
                      <i class="fa fa-store"></i>
                      <p>Negócios</p>
                    </a>
                  </li>

                  <li class="{{(url()->current() == route('painel.planos.index') ? 'active' : '')}}">
                    <a href="{{route('painel.planos.index')}}">
                        <i class="fa fa-check"></i>
                        <p>Planos</p>
                    </a>
                  </li>

                  <li class="{{(url()->current() == route('painel.negocios.pedidos') ? 'active' : '')}}">
                    <a href="{{route('painel.negocios.pedidos')}}">
                        <i class="fa fa-shopping-bag"></i>
                        <p>Pedidos</p>
                    </a>
                  </li>

                  <li class="{{(url()->current() == route('painel.assinatura.index') ? 'active' : '')}}">
                    <a href="{{route('painel.assinatura.index')}}">
                        <i class="fa fa-credit-card"></i>
                        <p>Assinatura</p>
                    </a>
                  </li>

                </ul>
              <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
            </div>

            <div class="main-panel ps" id="main-panel">
              <!-- Navbar -->
              <nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
                <div class="container-fluid">
                  <div class="navbar-wrapper">
                    <div class="navbar-toggle">
                      <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                      </button>
                    </div>
                    <a class="navbar-brand" href="#pablo"></a>
                  </div>

                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                  </button>

                  <div class="collapse navbar-collapse justify-content-end" id="navigation">

                    <ul class="navbar-nav">
                      <li class="nav-item">
                        <a class="nav-link" target="_blank" href="{{route('site.index')}}">
                          <i class="fa fa-eye"></i>
                          <p>
                            <span class="d-lg-none d-md-block">Site</span>
                          </p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" href="{{route('painel.perfil')}}">
                          <i class="now-ui-icons users_single-02"></i>
                          <p>
                            <span class="d-lg-none d-md-block">Perfil</span>
                          </p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <form method="POST" action="{{route('logout')}}">@csrf
                            <a class="nav-link" href="{{route('painel.perfil')}}" onclick="event.preventDefault();
                                this.closest('form').submit();">
                            <i class="fa fa-sign-out-alt"></i>
                            <p>
                                <span class="d-lg-none d-md-block">Sair</span>
                            </p>
                            </a>
                        </form>
                      </li>
                    </ul>
                  </div>
                </div>
              </nav>

              <!-- End Navbar -->
                @yield('header')

              <div class="content">
                @yield('conteudo')
              </div>

              <footer class="footer">
                <div class=" container-fluid ">
                  <nav>
                    <ul>
                      <li>
                        <a href="{{route('painel.index')}}">
                          SisPedidos
                        </a>
                      </li>

                    </ul>
                  </nav>

                  <div class="copyright" id="copyright">
                    © <script>
                      document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
                    </script>2021, <a href="https://www.sispedidos.com.br" target="_blank">sispedidos.com.br</a>.
                  </div>
                </div>
              </footer>
            <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="ModalDialog" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
            </div>
        </div>

          <script src="{{asset('assets/plugins/js/jquery.min.js')}}"></script>
          <script src="{{asset('assets/plugins/js/popper.min.js')}}"></script>
          <script src="{{asset('assets/plugins/js/bootstrap.min.js')}}"></script>
          <script src="{{asset('assets/plugins/js/perfect-scrollbar.jquery.min.js')}}"></script>
          <script src="{{asset('assets/plugins/js/now-ui-dashboard.js')}}"></script>
          <script src="{{asset('assets/plugins/js/select2/js/select2.full.min.js')}}"></script>
          <script src="{{asset('assets/plugins/js/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
          <script src="{{asset('assets/plugins/js/datepicker/locales/bootstrap-datepicker.pt-BR.min.js')}}"></script>
          <script src="{{asset('assets/plugins/js/sweetalert.js')}}"></script>
          <script src="{{asset('assets/plugins/js/jquery.mask.min.js')}}"></script>
          <script src="{{asset('assets/plugins/js/jquery.form.js')}}"></script>
          <script src="{{asset('assets/js/scripts.js')}}"></script>
          <script src="{{asset('assets/js/produtos.js')}}"></script>

          @yield('scripts')

    </body>
</html>
