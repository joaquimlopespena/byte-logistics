/**
 * Entry só para Echo/Reverb no painel AdminLTE.
 * Com `laravel_asset_bundling` = false, o `app.js` não é carregado; sem isto `window.Echo` não existe.
 */
import './echo';
