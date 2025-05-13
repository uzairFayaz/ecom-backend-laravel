import React, { useEffect, useState } from 'react';

const ProductByCategory = () => {
    const [products, setProducts] = useState([]);

    useEffect(() => {
        const el = document.getElementById('product-by-category-app');
        if (el) {
            const rawData = el.getAttribute('data-products');
            try {
                setProducts(JSON.parse(rawData));
            } catch (e) {
                console.error('Failed to parse products:', e);
            }
        }
    }, []);

    if (products.length === 0) {
        return <p>No products found in this category.</p>;
    }

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-4">
            {products.map(product => (
                <div
                    key={product.id}
                    className="border rounded-lg shadow hover:shadow-md transition"
                >
                    <img
                        src={product.image_url || 'https://via.placeholder.com/150'}
                        alt={product.product_name}
                        className="w-full h-48 object-cover rounded-t"
                    />
                    <div className="p-4">
                        <h3 className="font-semibold text-lg">{product.product_name}</h3>
                        <p className="text-gray-700">Price: ₹{product.price}</p>
                        <p className="text-sm text-gray-500 mt-1">
                            {product.description?.slice(0, 80) || 'No description'}
                        </p>
                    </div>
                </div>
            ))}
        </div>
    );
};

export default ProductByCategory;
