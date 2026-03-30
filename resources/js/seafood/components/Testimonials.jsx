import { testimonials } from "../data/menuData";

export default function Testimonials() {
    return (
        <section className="bg-gradient-to-b from-blue-50 to-slate-50 py-12 md:py-20">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-8 text-center md:mb-12">
                    <span className="mb-3 inline-block rounded-full bg-green-100 px-4 py-1.5 text-xs font-bold text-green-700 md:text-sm">
                        TESTIMONIAL
                    </span>
                    <h2 className="mb-3 text-3xl font-extrabold text-gray-900 md:text-4xl">
                        Kata Pelanggan Kami
                    </h2>
                    <p className="text-sm text-gray-500 md:text-base">
                        Pengalaman terbaik pelanggan kami bersama hidangan seafood pilihan.
                    </p>
                </div>

                {testimonials.length > 0 ? (
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
                        {testimonials.map((review) => (
                            <div
                                key={review.id}
                                className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg md:p-6"
                            >
                                <div className="mb-4">
                                    <h4 className="text-sm font-bold text-gray-900">{review.customer_name}</h4>
                                </div>
                                <div className="mb-3 flex gap-0.5">
                                    {Array.from({ length: review.rating }).map((_, index) => (
                                        <span key={index} className="text-sm text-yellow-400">★</span>
                                    ))}
                                </div>
                                <p className="text-sm leading-relaxed text-gray-600">{review.content}</p>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="rounded-3xl border border-dashed border-green-200 bg-white p-8 text-center">
                        <p className="text-base font-semibold text-slate-900">
                            Testimoni pelanggan akan tampil di sini.
                        </p>
                        <p className="mt-2 text-sm text-slate-600">
                            Bagian ini disiapkan untuk menampilkan ulasan terbaik dari pelanggan seafood kami.
                        </p>
                    </div>
                )}
            </div>
        </section>
    );
}
