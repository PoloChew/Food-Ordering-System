function openCheckoutModal() {
    document.getElementById('modal-checkout').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// 🌟 注意：这里删除了 deleteCookie 函数，因为我们不再需要清除座位了

function togglePaymentFields() {
    var method = document.getElementById('payment_method').value;
    
    document.getElementById('ewallet-section').classList.add('hidden');
    document.getElementById('bank-section').classList.add('hidden');

    if(method === 'E-wallet') {
        document.getElementById('ewallet-section').classList.remove('hidden');
    } else if (method === 'Online Banking') {
        document.getElementById('bank-section').classList.remove('hidden');
    }
}

function selectBank(element, bankName) {
    document.getElementById('bank_name').value = bankName;
    var options = document.querySelectorAll('.bank-option');
    options.forEach(opt => opt.classList.remove('selected'));
    element.classList.add('selected');
}

function submitOrder() {
    var name = document.getElementById('cust_name').value;
    var table = document.getElementById('table_num').value;
    var method = document.getElementById('payment_method').value;
    var verify = document.getElementById('human_ans').value;
    var pax = document.getElementById('pax_val').value;

    if(name === "" || method === "") {
        alert("Please enter Name and select Payment Method.");
        return;
    }

    if(verify !== "20") {
        alert("Wrong Human Verify answer! (Hint: 12 + 8 = 20)");
        return;
    }

    var formData = new FormData();
    formData.append('name', name);
    formData.append('table', table);
    formData.append('method', method);
    formData.append('pax', pax);

    // 🌟🌟🌟 NEW DELIVERY LOGIC ADDED HERE 🌟🌟🌟
    // Only runs if the user is in "Delivery" mode
    if (table === "Delivery") {
        var addressElem = document.getElementById('delivery_address');
        var contactElem = document.getElementById('contact_number');
        
        // Ensure these input fields actually exist on the page
        if (addressElem && contactElem) {
            var address = addressElem.value.trim();
            var phone = contactElem.value.trim();

            if (address === "" || phone === "") {
                alert("Please fill in Delivery Address and Contact Number.");
                return; // Stop here if empty
            }

            // Append to formData so PHP can read $_POST['address'] and $_POST['phone']
            formData.append('address', address);
            formData.append('phone', phone);
        }
    }
    // 🌟🌟🌟 END OF DELIVERY LOGIC 🌟🌟🌟

    // --- E-Wallet Logic ---
    if (method === 'E-wallet') {
        var country = document.getElementById('country_code').value;
        var phoneRaw = document.getElementById('ewallet_phone').value;
        var pin = document.getElementById('ewallet_pin').value;
        
        if(phoneRaw === "") {
            alert("Please enter phone number.");
            return;
        }

        var isValidPhone = false;
        if(country === "+60") {
            isValidPhone = /^1[0-9]-?[0-9]{7,8}$/.test(phoneRaw);
        } else if (country === "+1") {
            isValidPhone = /^\d{10}$/.test(phoneRaw);
        } else if (country === "+81") {
            isValidPhone = /^0\d{9,10}$/.test(phoneRaw);
        } else if (country === "+82") {
            isValidPhone = /^01\d{8,9}$/.test(phoneRaw);
        }

        if(!isValidPhone) {
            alert("Invalid phone number format for " + country);
            return;
        }

        if(pin.length !== 6 || isNaN(pin)) {
            alert("TNG PIN must be exactly 6 digits.");
            return;
        }

        formData.append('phone', country + phoneRaw);
        formData.append('pin', pin);

    } else if (method === 'Online Banking') {
        var bank = document.getElementById('bank_name').value;
        var acc = document.getElementById('bank_acc').value;
        var bPin = document.getElementById('bank_pin').value;

        if(bank === "") {
            alert("Please select a Bank by clicking on the logo.");
            return;
        }
        
        // Validation: Must be digits and longer than 9
        if(!/^\d{10,}$/.test(acc)) {
            alert("Bank Account Number must be numeric and longer than 9 digits.");
            return;
        }

        if(acc === "" || bPin === "") {
            alert("Please fill in Bank Account and PIN.");
            return;
        }
        formData.append('bank', bank);
        formData.append('account', acc);
        formData.append('pin', bPin);
    }

    fetch('checkout_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('modal-checkout');
            
            var successContainer = document.getElementById('success-message-container');
            var title = document.getElementById('success-title');
            var icon = document.getElementById('success-icon');
            
            if(data.payment_status === 'Pending') {
                // 现金支付情况
                icon.innerHTML = "📝";
                title.innerText = "Order Pending Payment";
                title.style.color = "#d4af37";
                
                // 如果是 Delivery 且选了现金支付 (虽然通常 Delivery 需要预付，但以防万一保留逻辑)
                if (table === "Delivery") {
                    successContainer.innerHTML = `
                        <p style="color: #fff; font-size: 18px; margin-bottom: 10px;">Order Received.</p>
                        <p style="color: #d4af37; font-weight: bold;">🛵 Est. Delivery: 30-45 Mins</p>
                        <p style="color: #aebcb9; font-size: 14px;">Please pay upon delivery.</p>
                    `;
                } else {
                    successContainer.innerHTML = `
                        <p style="color: #fff; font-size: 18px; margin-bottom: 10px;">Please proceed to the counter to pay.</p>
                        <p style="color: #aebcb9;">Enjoy your meal and welcome again!</p>
                    `;
                }

            } else {
                // 电子支付成功情况
                icon.innerHTML = "🎉";
                title.innerText = "Payment Successful!";
                title.style.color = "#4CAF50";
                
                // 🌟🌟🌟 这里是重点修改：区分 Delivery 和 Dine-in 的显示信息 🌟🌟🌟
                if (table === "Delivery") {
                    successContainer.innerHTML = `
                        <p style="color: #fff; font-size: 16px; margin-bottom: 5px;">Thank you for your order!</p>
                        <p style="color: #d4af37; font-size: 20px; font-weight: bold; margin: 15px 0;">
                            🛵 Est. Delivery Time: <br>30 - 45 Mins
                        </p>
                        <p style="color: #aebcb9; font-size: 14px;">We will process your delivery shortly.</p>
                    `;
                } else {
                    successContainer.innerHTML = `
                        <p style="color: #aebcb9;">Thank you for dining with TAR UMT Cafe.</p>
                        <p style="color: #aebcb9;">The kitchen is preparing your meal.</p>
                    `;
                }
            }

            document.getElementById('modal-success').style.display = 'flex';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('System error, please try again.');
    });
}