@extends('layouts.admin')

@section('title', 'Add Items to Order #' . $order->id)

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-amber-500 transition-colors">← Back to Order #{{ $order->id }}</a>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl text-white tracking-wider mb-1">Add Items to Order</h1>
        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Order #{{ $order->id }} - Table {{ $order->table->table_number ?? 'N/A' }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 mb-6">
                <h3 class="font-semibold text-white mb-4">Current Order</h3>
                <p class="text-sm text-gray-500 mb-2">Table {{ $order->table->table_number ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500 mb-4">{{ $order->orderItems->count() }} items</p>
                
                <ul class="space-y-2 mb-4">
                    @foreach($order->orderItems as $oi)
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-300">{{ $oi->quantity }}× {{ $oi->item->name }}</span>
                            <span class="text-gray-500">${{ number_format($oi->subtotal, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                
                <div class="border-t border-gray-700 pt-3">
                    <div class="flex justify-between font-bold text-white">
                        <span>Current Total:</span>
                        <span class="text-amber-500">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Selected Items Preview -->
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6" id="selected-items-panel" style="display: none;">
                <h3 class="font-semibold text-white mb-4">Items to Add</h3>
                <ul id="selected-items-list" class="space-y-2 mb-4"></ul>
                <div class="border-t border-gray-700 pt-3">
                    <div class="flex justify-between font-bold text-white">
                        <span>New Items Total:</span>
                        <span id="new-items-total" class="text-amber-500">$0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Items Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.orders.store-items', $order) }}" method="POST" id="add-items-form">
                @csrf
                
                <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6">
                    <h3 class="font-semibold text-white mb-4">Select Items to Add</h3>
                    
                    @foreach($categories as $category)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-400 mb-3 uppercase text-sm tracking-wider">{{ $category->name }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($category->items as $item)
                                    <div class="border border-gray-700 rounded-lg p-4 hover:bg-[#252525] cursor-pointer item-card transition-colors" 
                                         data-item-id="{{ $item->id }}"
                                         data-item-name="{{ $item->name }}"
                                         data-item-price="{{ $item->show_price ? $item->price : 0 }}"
                                         data-show-price="{{ $item->show_price }}">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-medium text-white">{{ $item->name }}</div>
                                                @if($item->show_price)
                                                    <div class="text-amber-500 font-medium">${{ number_format($item->price, 2) }}</div>
                                                @else
                                                    <div class="text-gray-500 italic">Price on request</div>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" class="qty-btn minus bg-[#0f0f0f] hover:bg-gray-700 text-white w-8 h-8 rounded transition-colors" data-action="minus">-</button>
                                                <span class="qty-display w-8 text-center font-medium text-white" data-qty="0">0</span>
                                                <button type="button" class="qty-btn plus bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] w-8 h-8 rounded transition-colors" data-action="plus">+</button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}" disabled class="item-input">
                                        <input type="hidden" name="items[{{ $item->id }}][quantity]" value="0" disabled class="qty-input">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="border-t border-gray-700 pt-4 mt-4">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-[#0a0a0a] font-semibold py-2 px-6 rounded-lg transition-colors" id="submit-btn" disabled>
                            Add Items to Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const selectedItems = {};
        let newItemsTotal = 0;

        function updateSelectedItemsPanel() {
            const panel = document.getElementById('selected-items-panel');
            const list = document.getElementById('selected-items-list');
            const totalDisplay = document.getElementById('new-items-total');
            const submitBtn = document.getElementById('submit-btn');
            
            const items = Object.values(selectedItems).filter(item => item.qty > 0);
            
            if (items.length === 0) {
                panel.style.display = 'none';
                submitBtn.disabled = true;
                return;
            }
            
            panel.style.display = 'block';
            submitBtn.disabled = false;
            
            list.innerHTML = items.map(item => `
                <li class="flex justify-between text-sm">
                    <span>${item.qty}× ${item.name}</span>
                    <span class="text-gray-500">$${item.subtotal.toFixed(2)}</span>
                </li>
            `).join('');
            
            newItemsTotal = items.reduce((sum, item) => sum + item.subtotal, 0);
            totalDisplay.textContent = '$' + newItemsTotal.toFixed(2);
        }

        document.querySelectorAll('.item-card').forEach(card => {
            const itemId = card.dataset.itemId;
            const itemName = card.dataset.itemName;
            const itemPrice = parseFloat(card.dataset.itemPrice) || 0;
            const showPrice = card.dataset.showPrice === '1';
            
            const qtyDisplay = card.querySelector('.qty-display');
            const itemInput = card.querySelector('.item-input');
            const qtyInput = card.querySelector('.qty-input');
            
            selectedItems[itemId] = { id: itemId, name: itemName, qty: 0, price: itemPrice, subtotal: 0 };

            card.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    
                    let qty = parseInt(qtyDisplay.dataset.qty);
                    
                    if (btn.dataset.action === 'plus') {
                        qty++;
                    } else if (btn.dataset.action === 'minus' && qty > 0) {
                        qty--;
                    }
                    
                    qtyDisplay.dataset.qty = qty;
                    qtyDisplay.textContent = qty;
                    
                    itemInput.disabled = qty === 0;
                    qtyInput.disabled = qty === 0;
                    qtyInput.value = qty;
                    
                    selectedItems[itemId].qty = qty;
                    selectedItems[itemId].subtotal = showPrice ? qty * itemPrice : 0;
                    
                    if (qty > 0) {
                        card.classList.add('bg-[#252525]', 'border-amber-500/50');
                    } else {
                        card.classList.remove('bg-[#252525]', 'border-amber-500/50');
                    }
                    
                    updateSelectedItemsPanel();
                });
            });
        });
    </script>
@endsection
