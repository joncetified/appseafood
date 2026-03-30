import { companyProfile } from "../data/menuData";

export default function Footer() {
    const businessName = companyProfile?.business_name && companyProfile.business_name !== "Seafood App"
        ? companyProfile.business_name
        : "Seafood";
    const about = companyProfile?.about || "Platform pemesanan seafood online untuk memudahkan pelanggan memilih menu, membuat pesanan, dan menikmati hidangan favorit dengan lebih praktis.";
    const footerSections = [
        {
            title: "Jam Operasional",
            lines: [
                companyProfile?.weekday_hours || "Informasi jam layanan akan segera tersedia",
                companyProfile?.weekend_hours || "Pemesanan online akan dibuka sesuai jadwal operasional",
            ],
        },
        {
            title: "Kontak",
            lines: [
                companyProfile?.address || "Informasi alamat akan segera tersedia",
                companyProfile?.phone || "Hubungi kami untuk pemesanan online",
                companyProfile?.email || "Email layanan pelanggan akan segera tersedia",
            ],
        },
    ];

    return (
        <footer id="footer" className="bg-gradient-to-b from-slate-900 to-slate-950 text-white">
            <div className="bg-slate-50">
                <svg viewBox="0 0 1440 80" className="w-full">
                    <path
                        d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,20 1440,40 L1440,80 L0,80 Z"
                        fill="#0f172a"
                    />
                </svg>
            </div>

            <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 md:py-16">
                <div className="grid grid-cols-1 gap-10 md:grid-cols-3 md:gap-16">
                    <div>
                        <div className="mb-4 flex items-center gap-2">
                            <h3 className="text-xl font-bold">{businessName}</h3>
                        </div>
                        <p className="mb-4 text-sm leading-relaxed text-gray-400">
                            {about}
                        </p>
                        <div className="flex gap-3">
                            {["Web", "IG", "WA"].map((label) => (
                                <span
                                    key={label}
                                    className="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-xs font-bold"
                                >
                                    {label}
                                </span>
                            ))}
                        </div>
                    </div>

                    {footerSections.map((section) => (
                        <div key={section.title}>
                            <h4 className="mb-4 text-lg font-bold">{section.title}</h4>
                            <ul className="space-y-2 text-sm text-gray-400">
                                {section.lines.map((line) => (
                                    <li key={line}>{line}</li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </div>

                <div className="mt-10 border-t border-white/10 pt-8 text-center">
                    <p className="text-sm text-gray-500">
                        {businessName} siap membantu pesanan seafood online Anda.
                    </p>
                </div>
            </div>
        </footer>
    );
}
