import $ from "jquery";
window.$ = window.jQuery = $;

import { loadSelect2CDN } from "./select2-init.js";
import { loadPersianDatepickerCDN } from "./persian-datepicker-loader.js";

document.addEventListener("DOMContentLoaded", async () => {
    try {
        await loadSelect2CDN();
        await loadPersianDatepickerCDN();
    } catch (error) {
        console.error("Error loading resources:", error);
    }
});
import "../css/app.css";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

async function subscribeToPush() {
    try {
        if (!("serviceWorker" in navigator) || !("PushManager" in window)) {
            console.warn("Push not supported");
            return { ok: false, reason: "unsupported" };
        }
        const reg = await navigator.serviceWorker.ready;
        const existing = await reg.pushManager.getSubscription();
        if (existing) {
            console.log("Already subscribed", existing);
            return { ok: true, reason: "already-subscribed" };
        }

        const permission = await Notification.requestPermission();
        console.log("Permission:", permission);
        if (permission !== "granted") {
            return { ok: false, reason: "permission-" + permission };
        }

        const vapidMeta = document.querySelector('meta[name="vapid-key"]');
        const vapidKey = vapidMeta ? vapidMeta.content : "";
        if (!vapidKey) {
            console.error("VAPID key missing!");
            return { ok: false, reason: "vapid-missing" };
        }

        const subscription = await reg.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(vapidKey),
        });

        const res = await fetch("/push/subscribe", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify(subscription),
        });
        const data = await res.json();
        console.log("Subscribe response:", data);
        return { ok: res.ok, reason: "subscribed", data };
    } catch (e) {
        console.error("Push subscribe failed:", e);
        return { ok: false, reason: "error", error: e };
    }
}
function urlBase64ToUint8Array(base64String) {
    const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, "+")
        .replace(/_/g, "/");
    const rawData = atob(base64);
    return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
}

window.subscribeToPush = subscribeToPush;

document
    .getElementById("enableNotifBtn")
    ?.addEventListener("click", subscribeToPush);

document.addEventListener("DOMContentLoaded", () => {

    if (window.__shouldSubscribePush) {
        subscribeToPush();
    }
});
