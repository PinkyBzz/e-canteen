import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';

const formatCurrency = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};

const MenuItem = ({ menu }) => {
    const isSoldOut = menu.stock != null && menu.stock <= 0;
    const [imageError, setImageError] = useState(false);
    const [isAdding, setIsAdding] = useState(false);
    
    const handleAddToCart = async (e) => {
        e.preventDefault();
        if (isSoldOut || isAdding) return;
        
        const url = window.ECANTEEN.cartAddUrl.replace('__ID__', menu.id);
        setIsAdding(true);

        try {
            const formData = new FormData();
            formData.append('_token', window.ECANTEEN.csrfToken);
            formData.append('quantity', '1');

            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            });

            const data = await res.json();
            
            if (res.ok) {
                if (window.showFloatingCart) window.showFloatingCart();
                if (window.updateCartBadge) window.updateCartBadge(1);
                setTimeout(() => setIsAdding(false), 1000);
            } else {
                alert(data.message || 'Error adding to cart');
                setIsAdding(false);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to connect to server');
            setIsAdding(false);
        }
    };

    return (
        <article className="bg-white rounded-3xl border border-zinc-200/80 shadow-sm hover:shadow-lg hover:border-zinc-300 transition-all duration-300 overflow-hidden flex flex-col">
            {/* Image Container */}
            <div className="relative aspect-[4/3] bg-zinc-50 flex items-center justify-center overflow-hidden">
                {!imageError && (menu.photo || menu.image) && (menu.photo || menu.image) !== 'default.jpg' ? (
                    <img 
                        src={`/storage/${menu.photo || menu.image}`} 
                        alt={menu.name}
                        className={`w-full h-full object-cover transition-transform duration-500 hover:scale-105 ${isSoldOut ? 'grayscale opacity-60' : ''}`}
                        onError={() => setImageError(true)}
                    />
                ) : (
                        <svg className="w-12 h-12 text-zinc-300" fill="none" stroke="currentColor" strokeWidth="1.2" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M4 7c0-1.1.9-2 2-2h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z"/>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M8 12h8M8 16h5"/>
                        </svg>
                )}
                
                {/* Category Badge */}
                <div className="absolute top-4 left-4">
                    <span className="px-3 py-1 bg-white/95 backdrop-blur-sm rounded-full text-xs font-semibold text-zinc-700 shadow-sm border border-zinc-100">
                        {menu.category}
                    </span>
                </div>

                {isSoldOut && (
                    <div className="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-10 flex items-center justify-center">
                        <span className="px-4 py-2 bg-zinc-900 text-white font-bold text-sm rounded-full uppercase tracking-wider">
                            Habis
                        </span>
                    </div>
                )}
            </div>

            {/* Content */}
            <div className="p-5 flex flex-col flex-grow">
                <div className="flex justify-between items-start gap-3 mb-1">
                    <h3 className="font-bold text-lg text-zinc-900 leading-tight">
                        {menu.name}
                    </h3>
                    {!isSoldOut && (
                        <div className="flex items-center gap-1 bg-amber-50 px-2 py-1.5 rounded-lg shrink-0 border border-amber-100/50">
                            <svg className="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span className="text-xs font-bold text-amber-700">4.8</span>
                        </div>
                    )}
                </div>

                {menu.warung && (
                    <div className="flex items-center gap-1.5 mb-2">
                        <svg className="w-3 h-3 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" strokeWidth="1.8" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M3 3h18v4H3zM5 7v12h14V7"/>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M9 21V12h6v9"/>
                        </svg>
                        <span className="text-xs font-semibold text-amber-600">{menu.warung.name}</span>
                    </div>
                )}

                <p className="text-sm text-zinc-500 line-clamp-2 mb-6 leading-relaxed">
                    {menu.description || 'Hidangan lezat untuk santapan Anda hari ini.'}
                </p>

                <div className="mt-auto flex items-center justify-between">
                    <div>
                        <span className="text-[11px] font-semibold text-zinc-400 block mb-0.5 uppercase tracking-wider">Harga</span>
                        <div className="font-bold text-lg text-zinc-900">
                            {formatCurrency(menu.price)}
                        </div>
                    </div>

                    {!isSoldOut && (
                        <button 
                            onClick={handleAddToCart}
                            disabled={isAdding}
                            className={`w-11 h-11 rounded-full flex items-center justify-center transition-all ${
                                isAdding 
                                ? 'bg-zinc-200 cursor-wait' 
                                : 'bg-zinc-900 hover:bg-zinc-700 hover:-translate-y-1 active:translate-y-0 shadow-md hover:shadow-xl'
                            }`}
                        >
                            {isAdding ? (
                                <svg className="w-5 h-5 text-zinc-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"/>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                            ) : (
                                <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" strokeWidth="1.8" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"/>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v4m2-2h-4"/>
                                </svg>
                            )}
                        </button>
                    )}
                </div>
            </div>
        </article>
    );
};

const MenuGrid = () => {
    const menus = window.ECANTEEN?.menus || [];
    if (menus.length === 0) return null;

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-12">
            {menus.map(menu => (
                <MenuItem key={menu.id} menu={menu} />
            ))}
        </div>
    );
};

const mountMenu = () => {
    const rootElement = document.getElementById('menu-react-root');
    if (rootElement) {
        const root = createRoot(rootElement);
        root.render(<MenuGrid />);
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mountMenu);
} else {
    mountMenu();
}
