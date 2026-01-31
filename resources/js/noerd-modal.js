document.addEventListener('alpine:init', () => {
    // Modal magic
    Alpine.magic('modal', () => {
        return (component, args = {}, source = null) => {
            const params = { modalComponent: component, arguments: args };
            if (source) params.source = source;
            Livewire.dispatch('noerdModal', params);
        };
    });

    // Alpine Stores
    Alpine.store('app', {
        currentId: null,
        modalOpen: false,
        setId(id) {
            this.currentId = id;
        }
    });
});

document.addEventListener('set-app-id', (event) => {
    Alpine.store('app').setId(event.detail.id);
});

document.addEventListener('modal-closed-global', () => {
    Alpine.store('app').modalOpen = false;
});
