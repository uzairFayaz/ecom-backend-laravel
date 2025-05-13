import './bootstrap';
import './components/Example';
import React from 'react';
import ProductsByCategory from './components/ProductsByCategory';
import { createRoot } from 'react-dom/client';
import CategoriesApp from './components/CategoriesApp';

const categoriesAppElement = document.getElementById('CategoriesApp');
if (categoriesAppElement) {
    const categories = JSON.parse(categoriesAppElement.dataset.categories || '[]');
    createRoot(categoriesAppElement).render(<CategoriesApp categories={categories} />);
}
const productsContainer = document.getElementById('products-by-category-app');
if (productsContainer) {
    const productsRoot = createRoot(productsContainer);
    productsRoot.render(<ProductsByCategory />);
}
