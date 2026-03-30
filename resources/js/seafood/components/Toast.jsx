import { useEffect, useState } from "react";

export default function Toast({ message, isVisible, onClose }) {
    const [show, setShow] = useState(false);

    useEffect(() => {
        if (!isVisible) {
            setShow(false);
            return undefined;
        }

        setShow(true);

        const timer = window.setTimeout(() => {
            setShow(false);
            window.setTimeout(onClose, 300);
        }, 2000);

        return () => window.clearTimeout(timer);
    }, [isVisible, onClose]);

    if (!isVisible) {
        return null;
    }

    return (
        <div
            className={`fixed bottom-6 left-1/2 z-[100] -translate-x-1/2 transition-all duration-300 ${
                show ? "translate-y-0 opacity-100 scale-100" : "translate-y-4 opacity-0 scale-95"
            }`}
        >
            <div className="flex items-center gap-2 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-3 text-sm font-medium text-white shadow-xl shadow-green-500/25">
                <span className="text-lg">OK</span>
                {message}
            </div>
        </div>
    );
}
