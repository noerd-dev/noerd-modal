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

## Example Usage

Opening a Livewire component in a modal via a button
```html
<button type="button"
    @click="$modal('livewire-component-name')">
    Open Modal
</button>
```

Add parameters like a ID to the modal
```html
<button type="button"
    @click="$modal('livewire-component-name', { exampleId: 'value1' })">
    Open Modal
</button>
```

