function formatPrice(price) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(price);
}

export default function Cart({
    isOpen,
    onClose,
    cartItems,
    onUpdateQuantity,
    onRemoveItem,
    onClearCart,
    onCheckout,
}) {
    const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
    const subtotal = cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const total = subtotal * 1.1;

    return (
        <>
            {isOpen && (
                <div
                    className="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm transition-opacity"
                    onClick={onClose}
                    aria-hidden="true"
                />
            )}

            <div
                className={`fixed right-0 top-0 z-50 h-full w-full bg-white shadow-2xl transition-transform duration-500 ease-out sm:w-[420px] ${
                    isOpen ? "translate-x-0" : "translate-x-full"
                }`}
            >
                <div className="bg-gradient-to-r from-blue-600 to-cyan-500 p-5 text-white md:p-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-xl font-bold">Keranjang</h2>
                            <p className="mt-1 text-sm text-blue-100">
                                {totalItems} item dipilih
                            </p>
                        </div>
                        <button
                            type="button"
                            onClick={onClose}
                            className="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-lg text-white transition-colors hover:bg-white/30"
                        >
                            X
                        </button>
                    </div>
                </div>

                <div className="flex-1 space-y-3 overflow-y-auto p-4 md:p-5" style={{ maxHeight: "calc(100vh - 250px)" }}>
                    {cartItems.length === 0 ? (
                        <div className="py-16 text-center">
                            <span className="mb-4 block text-6xl">Seafood</span>
                            <p className="mb-2 text-lg font-medium text-gray-400">
                                Keranjang masih kosong
                            </p>
                            <p className="text-sm text-gray-300">
                                Pilih hidangan favorit Anda untuk mulai membuat pesanan.
                            </p>
                        </div>
                    ) : (
                        <>
                            {cartItems.map((item) => (
                                <div
                                    key={item.id}
                                    className="group flex items-start gap-3 rounded-xl bg-gray-50 p-3 transition-colors hover:bg-blue-50/50 md:p-4"
                                >
                                    <div className="mt-1 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 md:h-14 md:w-14">
                                        Img
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <h4 className="truncate text-sm font-semibold text-gray-900">
                                            {item.name}
                                        </h4>
                                        <p className="mt-1 text-sm font-bold text-blue-600">
                                            {formatPrice(item.price)}
                                        </p>
                                        <div className="mt-2 flex items-center gap-2">
                                            <button
                                                type="button"
                                                onClick={() => onUpdateQuantity(item.id, item.quantity - 1)}
                                                className="flex h-7 w-7 items-center justify-center rounded-lg border border-gray-200 bg-white text-sm font-bold text-gray-600 transition-colors hover:border-blue-400 hover:bg-blue-50"
                                            >
                                                -
                                            </button>
                                            <span className="w-8 text-center text-sm font-bold text-gray-900">
                                                {item.quantity}
                                            </span>
                                            <button
                                                type="button"
                                                onClick={() => onUpdateQuantity(item.id, item.quantity + 1)}
                                                className="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-sm font-bold text-white transition-colors hover:bg-blue-700"
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>
                                    <div className="flex flex-col items-end gap-2 text-right">
                                        <button
                                            type="button"
                                            onClick={() => onRemoveItem(item.id)}
                                            className="text-lg text-gray-300 transition-colors hover:text-red-500"
                                        >
                                            X
                                        </button>
                                        <p className="whitespace-nowrap text-sm font-bold text-gray-900">
                                            {formatPrice(item.price * item.quantity)}
                                        </p>
                                    </div>
                                </div>
                            ))}

                            <button
                                type="button"
                                onClick={onClearCart}
                                className="w-full py-2 text-center text-sm font-medium text-red-400 transition-colors hover:text-red-600"
                            >
                                Kosongkan Keranjang
                            </button>
                        </>
                    )}
                </div>

                {cartItems.length > 0 && (
                    <div className="absolute bottom-0 left-0 right-0 space-y-4 border-t border-gray-100 bg-white p-4 md:p-5">
                        <div className="space-y-2">
                            <div className="flex justify-between text-sm text-gray-500">
                                <span>Subtotal ({totalItems} item)</span>
                                <span>{formatPrice(subtotal)}</span>
                            </div>
                            <div className="flex justify-between text-sm text-gray-500">
                                <span>Pajak (10%)</span>
                                <span>{formatPrice(subtotal * 0.1)}</span>
                            </div>
                            <div className="flex justify-between border-t border-dashed border-gray-200 pt-2">
                                <span className="text-lg font-bold text-gray-900">Total</span>
                                <span className="bg-gradient-to-r from-blue-700 to-cyan-600 bg-clip-text text-xl font-extrabold text-transparent">
                                    {formatPrice(total)}
                                </span>
                            </div>
                        </div>

                        <button
                            type="button"
                            onClick={onCheckout}
                            className="w-full rounded-xl bg-gradient-to-r from-orange-500 to-red-500 py-3.5 text-lg font-bold text-white transition-all active:scale-[0.98]"
                        >
                            Pesan Sekarang
                        </button>
                    </div>
                )}
            </div>
        </>
    );
}
