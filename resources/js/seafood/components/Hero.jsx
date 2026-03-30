import { companyProfile } from "../data/menuData";

export default function Hero() {
    const businessName = companyProfile?.business_name && companyProfile.business_name !== "Seafood App"
        ? companyProfile.business_name
        : "Seafood";
    const tagline = companyProfile?.tagline || "Pesan seafood favoritmu dengan cepat, praktis, dan langsung dari satu aplikasi.";

    return (
        <section className="relative flex h-[85vh] items-center justify-center overflow-hidden md:h-screen">
            <div
                className="absolute inset-0 bg-cover bg-center"
                style={{ backgroundImage: "url('/images/hero-seafood.jpg')" }}
            />
            <div className="absolute inset-0 bg-gradient-to-b from-blue-950/70 via-blue-900/50 to-slate-950/90" />

            <div className="relative z-10 mx-auto max-w-4xl px-4 text-center">
                <div className="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-sm">
                    <span className="h-2 w-2 rounded-full bg-emerald-400" />
                    <span className="text-xs font-medium text-white/90 md:text-sm">
                        Seafood segar, rasa terbaik, siap dipesan kapan saja
                    </span>
                </div>

                <h1 className="mb-4 mt-6 text-4xl font-extrabold leading-tight text-white sm:text-5xl md:mb-6 md:text-7xl">
                    {businessName}
                    <br />
                    <span className="bg-gradient-to-r from-cyan-400 via-blue-400 to-teal-400 bg-clip-text text-transparent">
                        Seafood Pilihan
                    </span>
                </h1>

                <p className="mx-auto mb-8 max-w-2xl text-base leading-relaxed text-blue-100/80 md:mb-10 md:text-xl">
                    {tagline}
                </p>

                <div className="flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a
                        href="#menu"
                        className="w-full rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 px-8 py-4 text-lg font-bold text-white transition-all hover:-translate-y-0.5 sm:w-auto"
                    >
                        Lihat Menu
                    </a>
                    <a
                        href="#footer"
                        className="w-full rounded-full border border-white/30 bg-white/10 px-8 py-4 text-lg font-semibold text-white transition-all hover:bg-white/20 sm:w-auto"
                    >
                        Hubungi Kami
                    </a>
                </div>

                <div className="mt-12 flex items-center justify-center gap-6 md:mt-16 md:gap-12">
                    {[
                        { value: "Fresh", label: "Bahan Harian" },
                        { value: "Fast", label: "Pesan Online" },
                        { value: "Taste", label: "Cita Rasa" },
                    ].map((stat) => (
                        <div key={stat.label} className="text-center">
                            <div className="text-2xl font-bold text-white md:text-3xl">{stat.value}</div>
                            <div className="text-xs text-blue-200/70 md:text-sm">{stat.label}</div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 120" className="w-full">
                    <path
                        d="M0,80 C360,120 720,40 1080,80 C1260,100 1380,60 1440,80 L1440,120 L0,120 Z"
                        fill="#f8fafc"
                    />
                </svg>
            </div>
        </section>
    );
}
