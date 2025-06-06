@extends('layouts.app')

@section('title', 'कार्ट - Chincha Piro')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-red-600 mb-8 nepali-font">तपाईंको कार्ट</h1>

    <!-- कार्ट आइटमहरू -->
    <div id="cart-content">
        @include('cart._cart_items')
    </div>

    <!-- सफलता सन्देश -->
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 animate-fade-in-down">
            {{ session('success') }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // AJAX मार्फत कार्ट अपडेट
    document.querySelectorAll('.cart-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // बटनलाई डिसेबल गर्नुहोस्
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> कृपया प्रतीक्षा गर्नुहोस्...';

            fetch(this.action, {
                method: this.method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(response => {
                if (!response.ok) throw response;
                return response.json();
            })
            .then(data => {
                if (data.html) {
                    document.getElementById('cart-content').innerHTML = data.html;
                }
                if (data.success) {
                    showNotification(data.success, 'success');
                }
                if (data.error) {
                    showNotification(data.error, 'error');
                }
                if (data.cart_count !== undefined) {
                    document.getElementById('cart-count').textContent = data.cart_count;
                }
            })
            .catch(async (error) => {
                console.error('AJAX Error:', error);
                let errorMessage = 'त्रुटि भयो';

                try {
                    // प्रतिक्रियाको लेख सन्देश लिनुहोस्
                    const text = await error.text();

                    try {
                        // JSON प्रतिक्रियाबाट सन्देश लिनुहोस्
                        const data = JSON.parse(text);
                        errorMessage = data.error || data.message || errorMessage;
                    } catch {
                        // JSON होइन भने सामान्य लेख प्रतिक्रिया लिनुहोस्
                        errorMessage = text.slice(0, 100); // पहिलो 100 अक्षर सम्म
                    }
                } catch {
                    // प्रतिक्रिया पढ्न असफल
                    errorMessage = 'नेटवर्क त्रुटि: प्रतिक्रिया पढ्न सकिएन';
                }

                showNotification(errorMessage, 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    });

    // AJAX मार्फत कार्ट सफा गर्ने
    document.querySelectorAll('.clear-cart-btn').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            if (!confirm('के तपाईं कार्ट सफा गर्न निश्चित हुनुहुन्छ?')) return;

            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> कृपया प्रतीक्षा गर्नुहोस्...';

            try {
                const response = await fetch(this.closest('form').action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw response;

                const data = await response.json();

                if (data.html) {
                    document.getElementById('cart-content').innerHTML = data.html;
                }
                if (data.success) {
                    showNotification(data.success, 'success');
                }
                if (data.cart_count !== undefined) {
                    document.getElementById('cart-count').textContent = data.cart_count;
                }
            } catch (error) {
                console.error('AJAX Error:', error);
                let errorMessage = 'त्रुटि भयो';

                try {
                    // JSON वा लेख प्रतिक्रिया लिनुहोस्
                    const text = await error.text();

                    try {
                        const data = JSON.parse(text);
                        errorMessage = data.error || data.message || errorMessage;
                    } catch {
                        errorMessage = text.slice(0, 100) || errorMessage;
                    }
                } catch {
                    errorMessage = 'नेटवर्क त्रुटि: प्रतिक्रिया पढ्न सकिएन';
                }

                showNotification(errorMessage, 'error');
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    });

    // 🎯 सन्देश प्रदर्शन फंक्शन
    function showNotification(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded shadow-lg z-50 transition-all duration-300 transform ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);

        // 3 सेकण्ड पछि सन्देश हटाउनुहोस्
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // 📦 प्रारम्भिक कार्ट संख्या अपडेट
    function loadCartCount() {
        fetch("{{ route('cart.count') }}")
            .then(res => res.json())
            .then(data => {
                const el = document.getElementById('cart-count');
                if (el) el.textContent = data.count;
            })
            .catch(err => {
                console.error("Cart count load error:", err);
            });
    }
    loadCartCount();
    setInterval(loadCartCount, 5000);
});
</script>
@endpush

@push('styles')
<style>
    /* Animation for notifications */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out;
    }
    /* Cart item hover effect */
    .cart-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sticky-top {
            position: static;
        }
    }
    .nepali-font {
        font-family: 'Noto Sans Devanagari', 'Preeti', sans-serif;
    }
</style>
@endpush
