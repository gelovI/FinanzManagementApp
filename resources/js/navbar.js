document.addEventListener('alpine:init', () => {
    Alpine.data('navbar', () => ({
        openNotifications: false,
        notificationsCount: 3, 
    }));
});
