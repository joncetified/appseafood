import "./bootstrap";
import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import App from "./seafood/App";

const container = document.getElementById("app");

if (container) {
    createRoot(container).render(
        <StrictMode>
            <App />
        </StrictMode>
    );
}

if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
        navigator.serviceWorker.register("/sw.js").catch(() => {
            // Keep the app usable even if service worker registration fails.
        });
    });
}
