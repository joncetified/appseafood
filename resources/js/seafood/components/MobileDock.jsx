export default function MobileDock({ cartItems, onCartClick }) {
    const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);

    return (
        <div
            className="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-2xl backdrop-blur md:hidden"
            style={{ paddingBottom: "calc(env(safe-area-inset-bottom, 0px) + 12px)" }}
        >
            <div className="mx-auto grid max-w-md grid-cols-3 gap-3">
                <a
                    href="#menu"
                    className="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3 text-center text-sm font-semibold text-slate-700"
                >
                    Menu
                </a>
                <a
                    href="/owner-login"
                    className="rounded-2xl border border-cyan-200 bg-cyan-50 px-3 py-3 text-center text-sm font-semibold text-cyan-700"
                >
                    Admin
                </a>
                <button
                    type="button"
                    onClick={onCartClick}
                    className="relative rounded-2xl bg-gradient-to-r from-orange-500 to-red-500 px-3 py-3 text-center text-sm font-semibold text-white"
                >
                    Keranjang
                    {totalItems > 0 && (
                        <span className="absolute right-2 top-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-yellow-300 px-1 text-[10px] font-bold text-slate-900">
                            {totalItems}
                        </span>
                    )}
                </button>
            </div>
        </div>
    );
}
