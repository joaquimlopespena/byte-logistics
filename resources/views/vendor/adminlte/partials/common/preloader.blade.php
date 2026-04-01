@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

<div class="{{ $preloaderHelper->makePreloaderClasses() }}" style="{{ $preloaderHelper->makePreloaderStyle() }}">

    @hasSection('preloader')

        @yield('preloader')

    @else

        <img src="{{ asset(config('adminlte.preloader.img.path', 'vendor/adminlte/dist/img/AdminLTELogo.png')) }}"
             class="{{ config('adminlte.preloader.img.effect', 'animation__shake') }}"
             alt="{{ config('adminlte.preloader.img.alt', 'AdminLTE Preloader Image') }}"
             @if (config('adminlte.preloader.img.width'))
                 width="{{ config('adminlte.preloader.img.width') }}"
             @endif
             @if (config('adminlte.preloader.img.height'))
                 height="{{ config('adminlte.preloader.img.height') }}"
             @endif
             style="animation-iteration-count:infinite; max-height: 72px; width: auto;">

    @endif

</div>
