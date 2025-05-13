import React, { useState, useEffect } from 'react';

const CategoriesApp = () => {
    const [categories, setCategories] = useState([]);
    const [selectedCategoryPath, setSelectedCategoryPath] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Fetch categories from the API
    useEffect(() => {
        const fetchCategories = async () => {
            try {
                setLoading(true);
                const response = await fetch('/api/categories');
                if (!response.ok) {
                    throw new Error('Failed to fetch categories');
                }
                const data = await response.json();
                console.log('Fetched Categories:', JSON.stringify(data, null, 2));
                setCategories(data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchCategories();
    }, []);

    const handleCategoryClick = (categoryId) => {
        console.log('Selected Category ID:', categoryId);
        setSelectedCategoryPath([...selectedCategoryPath, categoryId]);
    };

    const handleBackClick = () => {
        setSelectedCategoryPath(selectedCategoryPath.slice(0, -1));
    };

    if (loading) {
        return <p className="text-gray-500">Loading categories...</p>;
    }

    if (error) {
        return <p className="text-red-500">Error: {error}</p>;
    }

    if (!categories || categories.length === 0) {
        return (
            <p className="text-gray-500">
                No categories found.{' '}
                <a href="/products" className="text-indigo-600 hover:text-indigo-800">
                    View All Products
                </a>
            </p>
        );
    }

    // Navigate the category tree to find the current category
    let currentCategories = categories;
    let currentCategory = null;
    for (let i = 0; i < selectedCategoryPath.length; i++) {
        const categoryId = selectedCategoryPath[i];
        currentCategory = currentCategories.find(cat => cat.id == categoryId);
        if (!currentCategory) break;
        currentCategories = currentCategory.children || [];
    }

    // Determine what to display: subcategories or products
    const itemsToShow = currentCategory ? currentCategories : categories;
    const hasSubcategories = itemsToShow.length > 0;
    const productsToShow = currentCategory && !hasSubcategories ? currentCategory.products || [] : [];

    return (
        <div className="py-4">
            <h1 className="text-2xl font-semibold mb-4">Browse Categories</h1>

            {/* Back Button */}
            {selectedCategoryPath.length > 0 && (
                <button
                    onClick={handleBackClick}
                    className="mb-4 px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"
                >
                    Back
                </button>
            )}

            {/* Current Level: Subcategories */}
            {hasSubcategories && (
                <div className="mb-6">
                    <h2 className="text-lg font-medium mb-2">
                        {currentCategory ? `${currentCategory.category_name} Subcategories` : 'Categories'}
                    </h2>
                    <div className="flex flex-wrap gap-2">
                        {itemsToShow.map(category => (
                            <button
                                key={category.id}
                                onClick={() => handleCategoryClick(category.id)}
                                className="px-3 py-1 bg-gray-100 text-gray-800 rounded hover:bg-gray-200"
                            >
                                {category.category_name}
                            </button>
                        ))}
                    </div>
                </div>
            )}

            {/* Products */}
            {productsToShow.length > 0 && (
                <div>
                    <h2 className="text-lg font-medium mb-2">{currentCategory.category_name} Products</h2>
                    <div className="space-y-2">
                        {productsToShow.map(product => {
                            console.log('Rendering Product:', product);
                            return (
                                <div
                                    key={product.id}
                                    className="flex items-center justify-between p-2 bg-white border rounded"
                                >
                                    <div>
                                        <h3 className="text-gray-800">{product.product_name}</h3>
                                        <p className="text-gray-600 text-sm">
                                            ${parseFloat(product.price).toFixed(2)}
                                        </p>
                                    </div>
                                    <a
                                        href={`/products/${product.id}`}
                                        className="text-indigo-600 hover:text-indigo-800 text-sm"
                                    >
                                        View Details
                                    </a>
                                </div>
                            );
                        })}
                    </div>
                </div>
            )}

            {/* No Products Message */}
            {!hasSubcategories && productsToShow.length === 0 && currentCategory && (
                <p className="text-gray-500">No products found in this category.</p>
            )}
        </div>
    );
};

export default CategoriesApp;
