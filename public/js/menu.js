document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.add-to-cart').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Page reload hudaina

            const itemId = this.getAttribute('data-id');
            const price = this.getAttribute('data-price');
            const quantity = 1; // ya tespaxi user select garna milne banau

            fetch('/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    item_id: itemId,
                    expected_price: price,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                alert("Item added to cart");
                // Optional: cart icon update gara
            })
            .catch(error => {
                console.error("Add to cart error:", error);
            });
        });
    });
});
