import { categories } from "../data/menuData";

export default function CategoryFilter({ activeCategory, onCategoryChange }) {
    return (
        <div className="scrollbar-hide flex gap-2 overflow-x-auto px-1 pb-2 md:gap-3">
            {categories.map((category) => (
                <button
                    key={category.id}
                    type="button"
                    onClick={() => onCategoryChange(category.id)}
                    className={`flex items-center gap-2 whitespace-nowrap rounded-full px-4 py-2.5 text-sm font-medium transition-all duration-300 md:px-5 md:py-3 ${
                        activeCategory === category.id
                            ? "bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/25"
                            : "border border-gray-200 bg-white text-gray-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700"
                    }`}
                >
                    <span className="text-sm">{category.icon}</span>
                    {category.name}
                </button>
            ))}
        </div>
    );
}
