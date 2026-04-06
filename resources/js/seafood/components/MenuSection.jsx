import { useMemo, useState } from "react";
import CategoryFilter from "./CategoryFilter";
import MenuCard from "./MenuCard";
import { menuItems } from "../data/menuData";

export default function MenuSection({ onAddToCart }) {
    const [activeCategory, setActiveCategory] = useState("all");
    const [searchQuery, setSearchQuery] = useState("");

    const filteredItems = useMemo(() => {
        let items = menuItems;

        if (activeCategory !== "all") {
            items = items.filter((item) => item.category === activeCategory);
        }

        if (searchQuery.trim()) {
            const query = searchQuery.toLowerCase();
            items = items.filter(
                (item) =>
                    item.name.toLowerCase().includes(query) ||
                    (item.description ?? "").toLowerCase().includes(query)
            );
        }

        return items;
    }, [activeCategory, searchQuery]);

    return (
        <section id="menu" className="bg-slate-50 py-12 md:py-20">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-8 text-center md:mb-12">
                    <span className="mb-3 inline-block rounded-full bg-blue-100 px-4 py-1.5 text-xs font-bold text-blue-700 md:text-sm">
                        MENU KAMI
                    </span>
                    <h2 className="mb-3 text-3xl font-extrabold text-gray-900 md:mb-4 md:text-5xl">
                        Pilihan <span className="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">Hidangan</span> Terbaik
                    </h2>
                    <p className="mx-auto max-w-2xl text-sm text-gray-500 md:text-base">
                        Temukan aneka hidangan laut pilihan dan pesan langsung secara online dengan proses yang cepat dan mudah.
                    </p>
                </div>

                <div className="mx-auto mb-6 max-w-md md:mb-8">
                    <div className="relative">
                        <input
                            id="menu-search"
                            type="text"
                            value={searchQuery}
                            onChange={(event) => setSearchQuery(event.target.value)}
                            placeholder="Cari menu favorit..."
                            className="w-full rounded-full border border-gray-200 bg-white py-3 pl-4 pr-4 text-sm shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 md:py-3.5"
                        />
                    </div>
                </div>

                <div className="mb-8 md:mb-10">
                    <CategoryFilter
                        activeCategory={activeCategory}
                        onCategoryChange={setActiveCategory}
                    />
                </div>

                {filteredItems.length > 0 ? (
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-3 xl:grid-cols-4">
                        {filteredItems.map((item) => (
                            <MenuCard key={item.id} item={item} onAddToCart={onAddToCart} />
                        ))}
                    </div>
                ) : (
                    <div className="py-16 text-center">
                        <span className="mb-4 block text-6xl">Seafood</span>
                        <p className="mb-2 text-lg font-medium text-gray-400">
                            Menu sedang disiapkan
                        </p>
                        <p className="text-sm text-gray-300">
                            Tim kami sedang melengkapi daftar hidangan terbaik untuk Anda.
                        </p>
                        <button
                            type="button"
                            onClick={() => {
                                setSearchQuery("");
                                setActiveCategory("all");
                            }}
                            className="mt-4 text-sm font-medium text-blue-600 hover:underline"
                        >
                            Reset pencarian
                        </button>
                    </div>
                )}

                {filteredItems.length > 0 && (
                    <p className="mt-6 text-center text-sm text-gray-400 md:mt-8">
                        Menampilkan {filteredItems.length} menu
                    </p>
                )}
            </div>
        </section>
    );
}
