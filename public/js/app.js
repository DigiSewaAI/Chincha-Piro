// resources/js/app.js

// 1. Bootstrap लोड गर्ने (Laravel Echo, Livewire लाई समावेश गर्नुहोस्)
import './bootstrap';

// 2. Alpine.js आयात गर्नुहोस्
import Alpine from 'alpinejs';

// 3. Alpine.js सुरु गर्नुहोस् (केवल यदि पहिले नै सुरु भएको छैन भने)
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
} else {
    console.warn('Alpine.js पहिले नै सुरु भएको छ');
}

// 4. Livewire लाई सही ढंगले सुरु गर्नुहोस् (यदि आवश्यक भए)
if (typeof window.Livewire !== 'undefined') {
    window.Livewire.start();
}
