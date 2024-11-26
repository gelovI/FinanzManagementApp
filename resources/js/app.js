import "./bootstrap";

import "./navbar";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const fetchNotifications = () => {
        fetch("/notifications")
            .then((response) => response.json())
            .then((data) => {
                console.log("Fetched notifications:", data);
                // Zeige nur ungelesene Benachrichtigungen
                window.notifications = data.filter(
                    (notification) => !notification.is_read
                );
            })
            .catch((error) => console.error("Fetch Error:", error));
    };

    const markNotificationsAsRead = () => {
        fetch("/notifications/mark-as-read", {
            method: "PUT",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Mark-as-read response:", data);
                window.notifications = [];
            })
            .catch((error) =>
                console.error("Error marking notifications as read:", error)
            );
    };

    // Lade Benachrichtigungen beim Laden der Seite
    fetchNotifications();

    // Event für "Als gelesen markieren" hinzufügen
    document
        .querySelector("#mark-as-read-btn")
        ?.addEventListener("click", markNotificationsAsRead);
});
