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

    // --- E-Wallet 验证逻辑 ---
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
        
        // 🌟 验证：必须是数字，且长度大于 9 位 (即 10 位或更多)
        // \d{10,} 意思是：数字，重复10次或更多
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
            
            // 🌟🌟🌟 修改点：这里删除了 deleteCookie 和 setTimeout 🌟🌟🌟
            // 也就是说，座位和人数会被保留，用户需要手动点击 "Back to Home"

            var successContainer = document.getElementById('success-message-container');
            var title = document.getElementById('success-title');
            var icon = document.getElementById('success-icon');
            
            // 移除了 "System will refresh..." 的提示文字
            
            if(data.payment_status === 'Pending') {
                icon.innerHTML = "📝";
                title.innerText = "Order Pending Payment";
                title.style.color = "#d4af37";
                successContainer.innerHTML = `
                    <p style="color: #fff; font-size: 18px; margin-bottom: 10px;">Please proceed to the counter to pay.</p>
                    <p style="color: #aebcb9;">Enjoy your meal and welcome again!</p>
                `;
            } else {
                icon.innerHTML = "🎉";
                title.innerText = "Payment Successful!";
                title.style.color = "#4CAF50";
                successContainer.innerHTML = `
                    <p style="color: #aebcb9;">Thank you for dining with TAR UMT Cafe.</p>
                    <p style="color: #aebcb9;">The kitchen is preparing your meal.</p>
                `;
            }

            document.getElementById('modal-success').style.display = 'flex';
            
            // 🌟 不再自动跳转，让用户停留在成功页面，直到他们自己点击按钮
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('System error, please try again.');
    });
}