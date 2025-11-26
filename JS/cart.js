function openCheckoutModal() {
            document.getElementById('modal-checkout').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

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

            if (method === 'E-wallet') {
                var phone = document.getElementById('ewallet_phone').value;
                var pin = document.getElementById('ewallet_pin').value;
                var phoneRegex = /^01[0-9]-?[0-9]{7,8}$/;
                
                if(!phoneRegex.test(phone)) {
                    alert("Invalid Malaysia Phone Number format.");
                    return;
                }
                if(pin.length !== 6 || isNaN(pin)) {
                    alert("TNG PIN must be exactly 6 digits.");
                    return;
                }
                formData.append('phone', phone);
                formData.append('pin', pin);

            } else if (method === 'Online Banking') {
                var bank = document.getElementById('bank_name').value;
                var acc = document.getElementById('bank_acc').value;
                var bPin = document.getElementById('bank_pin').value;

                if(bank === "") {
                    alert("Please select a Bank by clicking on the logo.");
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
                            <p style="color: #aebcb9;">Thank you for dining with Nordic Taste.</p>
                            <p style="color: #aebcb9;">The kitchen is preparing your meal.</p>
                        `;
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