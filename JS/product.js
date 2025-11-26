var orderType = "<?= $type ?>";
        var selectedSeat = ""; 

        function setCookie(cname, cvalue, minutes) {
            const d = new Date();
            d.setTime(d.getTime() + (minutes * 60 * 1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
            }
            return "";
        }

        window.onload = function() {
            if (getCookie("popup_shown") === "") {
                if (orderType === 'dinein') document.getElementById('modal-dinein').style.display = 'flex';
                else if (orderType === 'takeaway') document.getElementById('modal-takeaway').style.display = 'flex';
            }
        };

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            
            if(modalId === 'modal-dinein') {
                setCookie("popup_shown", "true", 5);
                if(selectedSeat !== "") {
                    setCookie("user_seat", selectedSeat, 120); // 存2小时
                    document.getElementById('header-seat-display').innerText = "[" + selectedSeat + "]";
                }
            } else if (modalId === 'modal-takeaway') {
                setCookie("popup_shown", "true", 5);
                setCookie("user_seat", "Takeaway", 120);
                document.getElementById('header-seat-display').innerText = "[Takeaway]";
            }
        }

        // 🌟 选座逻辑更新
        function selectSeat(seatNum, element) {
            selectedSeat = seatNum; 
            document.getElementById('selected-seat-msg').innerText = "Selected Seat: " + seatNum;
            
            var allSeats = document.querySelectorAll('.seat');
            allSeats.forEach(function(s) {
                s.classList.remove('selected');
            });
            element.classList.add('selected');
        }

        function openProductDetail(card) {
            var id = card.getAttribute('data-id');
            var name = card.getAttribute('data-name');
            var price = card.getAttribute('data-price');
            var desc = card.getAttribute('data-desc');
            var img = card.getAttribute('data-img');

            document.getElementById('detail-id').value = id; 
            document.getElementById('detail-name').innerText = name;
            document.getElementById('detail-price').innerText = 'RM ' + price;
            document.getElementById('detail-desc').innerText = desc;
            document.getElementById('detail-img').src = img;
            document.getElementById('detail-qty').value = 1; 

            document.getElementById('modal-product-detail').style.display = 'flex';
        }

        function addToCart() {
            var productId = document.getElementById('detail-id').value;
            var quantity = document.getElementById('detail-qty').value;

            var formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('add_to_cart.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    closeModal('modal-product-detail');
                    document.getElementById('modal-success').style.display = 'flex';

                    var currentQty = parseInt(document.getElementById('cart-qty-display').innerText);
                    document.getElementById('cart-qty-display').innerText = currentQty + parseInt(quantity);

                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => { console.error('Error:', error); alert('Something went wrong!'); });
        }