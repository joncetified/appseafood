import { promotions } from "../data/menuData";

export default function PromoSection() {
    return (
        <section className="bg-white py-12 md:py-20">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-8 text-center md:mb-12">
                    <span className="mb-3 inline-block rounded-full bg-orange-100 px-4 py-1.5 text-xs font-bold text-orange-700 md:text-sm">
                        PROMO SPESIAL
                    </span>
                    <h2 className="text-3xl font-extrabold text-gray-900 md:text-4xl">
                        Penawaran Terbaik
                    </h2>
                </div>

                {promotions.length > 0 ? (
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
                        {promotions.map((promo) => (
                            <div
                                key={promo.id}
                                className="rounded-2xl bg-gradient-to-br from-orange-500 to-red-500 p-6 text-white shadow-xl md:p-8"
                            >
                                <h3 className="mb-2 text-lg font-bold md:text-xl">{promo.title}</h3>
                                <p className="text-sm leading-relaxed text-white/80">{promo.description}</p>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="rounded-3xl border border-dashed border-orange-200 bg-orange-50/50 p-8 text-center">
                        <p className="text-base font-semibold text-orange-800">
                            Promo spesial akan segera hadir.
                        </p>
                        <p className="mt-2 text-sm text-orange-700/80">
                            Nantikan penawaran menarik untuk menu seafood favorit Anda.
                        </p>
                    </div>
                )}
            </div>
        </section>
    );
}
