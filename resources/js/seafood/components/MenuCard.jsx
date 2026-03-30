function formatPrice(price) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(price);
}

export default function MenuCard({ item, onAddToCart }) {
    return (
        <div className="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-500 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-900/10">
            <div className="relative flex h-44 items-center justify-center overflow-hidden bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-50 md:h-52">
                <span className="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">
                    gambar menu
                </span>

                {item.badge && (
                    <div className="absolute left-3 top-3">
                        <span className="rounded-full bg-gradient-to-r from-orange-500 to-red-500 px-3 py-1 text-[10px] font-bold text-white md:text-xs">
                            {item.badge}
                        </span>
                    </div>
                )}

                {item.spicy && (
                    <div className="absolute right-3 top-3">
                        <span className="rounded-full bg-red-100 px-2 py-1 text-xs font-bold text-red-600">
                            Pedas
                        </span>
                    </div>
                )}

                {item.rating && (
                    <div className="absolute bottom-3 right-3 flex items-center gap-1 rounded-full bg-white/90 px-2.5 py-1 shadow-sm backdrop-blur-sm">
                        <span className="text-xs text-yellow-500">★</span>
                        <span className="text-xs font-bold text-gray-700">{item.rating}</span>
                    </div>
                )}
            </div>

            <div className="p-4 md:p-5">
                <div className="mb-2 flex items-start justify-between gap-2">
                    <h3 className="text-base font-bold leading-tight text-gray-900 transition-colors group-hover:text-blue-700 md:text-lg">
                        {item.name}
                    </h3>
                    {item.popular && (
                        <span className="whitespace-nowrap rounded-full bg-yellow-100 px-2 py-0.5 text-[10px] font-bold text-yellow-700">
                            Popular
                        </span>
                    )}
                </div>

                <p className="line-clamp-2 mb-4 text-xs leading-relaxed text-gray-500 md:text-sm">
                    {item.description}
                </p>

                <div className="flex items-center justify-between">
                    <span className="bg-gradient-to-r from-blue-700 to-cyan-600 bg-clip-text text-lg font-extrabold text-transparent md:text-xl">
                        {formatPrice(item.price)}
                    </span>
                    <button
                        type="button"
                        onClick={() => onAddToCart(item)}
                        className="rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 px-4 py-2 text-sm font-semibold text-white transition-all active:scale-95 hover:-translate-y-0.5 md:px-5 md:py-2.5"
                    >
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    );
}
