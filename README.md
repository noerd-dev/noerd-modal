# noerd/modal

A modal system for Livewire 4. Can open every livewire component in a modal. 

## Installation

```bash
composer require noerd/modal
```

## Configuration
Add Assets between your head tags.

```bash
<head>
...
<x-noerd::modal-assets/>
...
</head>
```

Add Modal 
```bash
<body>
<livewire:noerd-modal/> <!-- must be loaded before livewire components -->
...
</head>
```

## Usage


```html
<button
@click="$modal('livewire-component-name', { optionalParameter: 'value1' })">
{{ __('Add Element') }}
</button>
```


