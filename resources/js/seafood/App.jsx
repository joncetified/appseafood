import { useCallback, useState } from "react";
import Navbar from "./components/Navbar";
import Hero from "./components/Hero";
import PromoSection from "./components/PromoSection";
import MenuSection from "./components/MenuSection";
import Testimonials from "./components/Testimonials";
import Cart from "./components/Cart";
import Footer from "./components/Footer";
import Toast from "./components/Toast";
import MobileDock from "./components/MobileDock";

export default function App() {
    const [cartItems, setCartItems] = useState([]);
    const [isCartOpen, setIsCartOpen] = useState(false);
    const [toast, setToast] = useState({ message: "", visible: false });

    const showToast = useCallback((message) => {
        setToast({ message, visible: true });
    }, []);

    const hideToast = useCallback(() => {
        setToast({ message: "", visible: false });
    }, []);

    const addToCart = useCallback((item) => {
        setCartItems((prev) => {
            const existingItem = prev.find((cartItem) => cartItem.id === item.id);

            if (existingItem) {
                return prev.map((cartItem) =>
                    cartItem.id === item.id
                        ? { ...cartItem, quantity: cartItem.quantity + 1 }
                        : cartItem
                );
            }

            return [...prev, { ...item, quantity: 1 }];
        });

        showToast(`${item.name} ditambahkan ke keranjang.`);
    }, [showToast]);

    const updateQuantity = useCallback((id, quantity) => {
        setCartItems((prev) =>
            quantity <= 0
                ? prev.filter((item) => item.id !== id)
                : prev.map((item) => (item.id === id ? { ...item, quantity } : item))
        );
    }, []);

    const removeItem = useCallback((id) => {
        setCartItems((prev) => prev.filter((item) => item.id !== id));
    }, []);

    const clearCart = useCallback(() => {
        setCartItems([]);
    }, []);

    const checkout = useCallback(async () => {
        if (cartItems.length === 0) {
            showToast("Keranjang masih kosong.");
            return;
        }

        const customerName = window.prompt("Masukkan nama pemesan:");

        if (!customerName) {
            return;
        }

        const customerPhone = window.prompt("Masukkan nomor telepon pemesan:") ?? "";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

        const response = await fetch("/orders", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken ?? "",
                "Accept": "application/json",
            },
            body: JSON.stringify({
                customer_name: customerName,
                customer_phone: customerPhone,
                items: cartItems.map((item) => ({
                    id: item.id,
                    quantity: item.quantity,
                })),
            }),
        });

        if (!response.ok) {
            showToast("Pesanan gagal dibuat.");
            return;
        }

        const data = await response.json();
        clearCart();
        setIsCartOpen(false);
        showToast(`Pesanan ${data.order_number} berhasil dibuat.`);
    }, [cartItems, clearCart, showToast]);

    return (
        <div className="min-h-screen bg-slate-50 pb-24 text-slate-900 md:pb-0">
            <Navbar
                cartItems={cartItems}
                onCartClick={() => setIsCartOpen(true)}
            />
            <Hero />
            <PromoSection />
            <MenuSection onAddToCart={addToCart} />
            <Testimonials />
            <Footer />
            <MobileDock
                cartItems={cartItems}
                onCartClick={() => setIsCartOpen(true)}
            />
            <Cart
                isOpen={isCartOpen}
                cartItems={cartItems}
                onClose={() => setIsCartOpen(false)}
                onUpdateQuantity={updateQuantity}
                onRemoveItem={removeItem}
                onClearCart={clearCart}
                onCheckout={checkout}
            />
            <Toast
                message={toast.message}
                isVisible={toast.visible}
                onClose={hideToast}
            />
        </div>
    );
}
