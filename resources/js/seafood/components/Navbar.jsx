import { useEffect, useState } from "react";
import { companyProfile } from "../data/menuData";

export default function Navbar({ cartItems, onCartClick }) {
    const [scrolled, setScrolled] = useState(false);
    const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
    const businessName = "Seafood";
    const tagline = companyProfile?.tagline || "Online Ordering";

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 24);

        window.addEventListener("scroll", handleScroll);

        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    return (
        <nav
            className={`fixed inset-x-0 top-0 z-50 transition-all duration-300 ${
                scrolled
                    ? "bg-white/95 shadow-lg shadow-blue-900/5 backdrop-blur-md"
                    : "bg-transparent"
            }`}
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="flex h-16 items-center justify-between md:h-20">
                    <div className="flex items-center gap-2">
                        <div>
                            <h1
                                className={`text-lg font-bold transition-colors md:text-xl ${
                                    scrolled ? "text-blue-900" : "text-white"
                                }`}
                            >
                                {businessName}
                            </h1>
                            <p
                                className={`text-[10px] uppercase tracking-widest transition-colors md:text-xs ${
                                    scrolled ? "text-blue-600" : "text-blue-200"
                                }`}
                            >
                                {tagline}
                            </p>
                        </div>
                    </div>

                    <div className="flex items-center gap-4">
                        <a
                            href="#menu"
                            className={`hidden text-sm font-medium transition-colors hover:text-blue-400 md:block ${
                                scrolled ? "text-gray-700" : "text-white"
                            }`}
                        >
                            Menu
                        </a>
                        <a
                            href="/owner-login"
                            className={`hidden rounded-full border px-4 py-2 text-sm font-semibold transition-all md:block ${
                                scrolled
                                    ? "border-slate-200 bg-white text-slate-800 hover:border-slate-300"
                                    : "border-white/30 bg-white/10 text-white hover:bg-white/20"
                            }`}
                        >
                            Login Admin
                        </a>
                        <button
                            type="button"
                            onClick={onCartClick}
                            className="relative rounded-full bg-gradient-to-r from-orange-500 to-red-500 px-4 py-2 text-sm font-semibold text-white transition-all active:scale-95 md:px-5 md:py-2.5"
                        >
                            Keranjang
                            {totalItems > 0 && (
                                <span className="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-yellow-400 text-xs font-bold text-gray-900">
                                    {totalItems}
                                </span>
                            )}
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    );
}
