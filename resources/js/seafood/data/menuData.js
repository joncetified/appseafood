const appData = window.__SEAFOOD_APP_DATA__ ?? {};

export const categories = [
    { id: "all", name: "Semua", icon: "Menu" },
    ...((appData.categories ?? []).map((category) => ({
        id: category.slug,
        name: category.name,
        icon: category.name,
    }))),
];

export const menuItems = appData.menuItems ?? [];

export const promotions = appData.promotions ?? [];

export const testimonials = appData.testimonials ?? [];

export const companyProfile = appData.profile ?? null;
