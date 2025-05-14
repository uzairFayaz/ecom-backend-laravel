import React, { useState, useEffect } from 'react';

const CategoriesApp = () => {
    const [categories, setCategories] = useState({});
    const [path, setPath] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch('/api/categories')
            .then(res => res.json())
            .then(data => {
                const map = {};
                const flatten = (list, parentId = null ) => {
                    list.forEach(cat => {
                        map[cat.id] = { ...cat, parentId };
                        if (cat.children?.length) flatten(cat.children, cat.id);
                    });
                };

                flatten(data);
                setCategories(map);
                setLoading(false);
            })
            .catch(() => setLoading(false));
    }, []);

    const currentId = path[path.length - 1];
    const currentCat = currentId ? categories[currentId] : null;

    const subcategories = currentCat
        ? currentCat.children || []
        : Object.values(categories).filter(cat => !cat.parentId);

    const products = currentCat && subcategories.length === 0
        ? currentCat.products || []
        : [];

    if (loading) return <p>Loading...</p>;

    return (
        <div className="p-4">
            <h2 className="text-xl font-bold mb-2">
                {currentCat ? currentCat.category_name : 'Categories'}
            </h2>

            {path.length > 0 && (
                <button onClick={() => setPath(path.slice(0, -1))} className="mb-2 px-2 py-1 bg-gray-200 rounded">
                    Back
                </button>
            )}

            {subcategories.length > 0 ? (
                <div className="flex gap-2 mb-2">
                    {subcategories.map(cat => (
                        <button key={cat.id} onClick={() => setPath([...path, cat.id])} className="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">
                            {cat.category_name}
                        </button>
                    ))}
                </div>
            ) : products.length > 0 ? (
                <div>
                    <h3 className="text-lg font-medium mb-2">Products</h3>
                    <ul className="space-y-1">
                        {products.map(product => (
                            <li key={product.id} className="p-1 border rounded flex justify-between">
                                <div>
                                    <p>{product.product_name}</p>
                                    <p className="text-sm text-gray-600">${product.price}</p>
                                </div>
                                <a href={`/products/${product.id}`} className="text-blue-600">
                                    View
                                </a>
                            </li>
                        ))}
                    </ul>
                </div>
            ) : path.length > 0 && (
                <p className="text-gray-500">No products found.</p>
            )}
        </div>
    );
};

export default CategoriesApp;
