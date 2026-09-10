# AGENTS.md — noerd/modal

Contributor notes for humans and AI agents working on the Noerd Modal package. The rules for
building WITH noerd (lists, details, pages, modals, modules, tests) come from the `noerd/noerd`
Boost guideline and skills; the package-specific rules are in
`resources/boost/guidelines/core.blade.php`. Both are rendered into the host project's agent files
by `php artisan boost:update` — add `noerd/modal` to the `packages` array in `boost.json` by hand,
there is no install command that does it for you.

## What this package is

A stacked modal system for Livewire 4: one `noerd-modal` Livewire component holds the stack, any
Livewire component is opened on top of the current page through the `noerdModal` event (Alpine
magics `$modal` / `$modalRoute`, or `Noerd::modal()` / `Noerd::modalRoute()` from `noerd/noerd`),
optionally addressed by a named `Route::livewire()` route whose URL is rewritten while the modal is
open. It is a support package: no tenant app, no `app-configs/`, no migrations. It depends only on
Laravel and Livewire — `noerd/noerd` requires `noerd/modal`, never the other way round
(`tests/Feature/ModuleBoundaryTest.php`).

## Layout

- `src/Providers/NoerdModalServiceProvider.php` — config merge, views (`noerd` namespace),
  Livewire namespace `noerd-modal::`, the asset route, publish tag and commands (console only)
- `src/Http/Controllers/AssetController.php`, `src/Support/AssetManifest.php` — serve the bundle
  from `dist/build` through `GET /noerd-modal/{file}` (`noerd-modal.asset`)
- `src/Console/Commands/` — `PublishExampleCommand`, `PublishPanelCommand`
- `resources/views/components/` — `noerd-modal.blade.php` (the stack), `noerd-modal-assets.blade.php`,
  `modal/index.blade.php`, `modal/panel.blade.php` (the chrome), `example/` (demo components)
- `resources/js/noerd-modal.js` — Alpine magics, store `app`, ESC handling, URL stack
- `dist/build/` — the committed Vite bundle (manifest + hashed assets)
- `config/noerd-modal.php` — `position` (`center` | `right`)
- `tests/Feature/` (Pest), `tests/js/modal-source.mjs`, `resources/lang/de.json`

## Commands

- `php artisan noerd-modal:publish-example {--force}` — example components + demo route
- `php artisan noerd-modal:publish-panel {--force}` — publish the panel view for customisation
- `php artisan vendor:publish --tag=noerd-modal-config` — publish the config (on request only)

There is deliberately NO `noerd:install-modal` / `noerd:update-modal`: the config is merged and
published only on request, the bundle is served from the package directory (nothing is copied into
`public/`), the provider writes nothing into the host on boot (`ProviderBootTest`), and a command
built on the core's `HasModuleInstallation` / `RequiresNoerdInstallation` traits would fatal in a
standalone Livewire host because this package does not depend on `noerd/noerd`
(`NoerdModalCommandsTest`). A `--force` forwarded by `noerd:update-all --force` would also reset
the user's `position` setting. Do not add one.

## Working on the package

- Tests bind the HOST's `Tests\TestCase` (`uses(Tests\TestCase::class)`); run from the host root:
  `php artisan test --compact app-modules/noerd-modal/tests`. No database is touched.
  `ModalSourceTest` needs a `node` binary and skips otherwise.
- JavaScript: `npm run dev` inside the package writes the Vite hot file to the host's
  `public/vendor/noerd-modal/hot` (the vite config assumes `app-modules/noerd-modal`), and the
  assets component then loads from the dev server. After changing `resources/js/noerd-modal.js`
  run `npm run build` and commit `dist/build/` — it ships with the package.
- Format from the host project root with an explicit path: `vendor/bin/pint app-modules/noerd-modal`
  (a plain `--dirty` run silently skips submodule files); the package has no own `pint.json`.
- Livewire children inside a modal are re-mounted on every stack update (`@teleport`): keep
  `mount()` of anything that can live in a modal side-effect free.
- Keep the package independent: it must never `use` a class of `noerd/noerd` unguarded (the
  `StaticConfigHelper` probe for `quickCreate` is wrapped in try/catch on purpose).
- When a feature changes: update the tests, `README.md` and
  `resources/boost/guidelines/core.blade.php`
- Releasing: bump `"version"` in `composer.json` to the tag in the tagged commit
