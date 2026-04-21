const validationMessage = document.getElementById('validationMessage');

function getMoney() {
    return document.getElementById('money').value;
}

function updateView(data) {
    document.getElementById('identifier').textContent = data.identifier;
    document.getElementById('totalAmount').textContent = data.currentBalance;
    document.getElementById('restartPurchase').textContent = data.restartPurchase ? 'Yes' : 'No';
}

function addMoney() {
    fetch('/add-money', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-Token': getCsrfToken()
        },
        body: JSON.stringify({ money: getMoney() })
    })
    .then(res => res.json())
    .then(data => {
        if (data.amountNotValid) {
            validationMessage.textContent = 'Amount not valid';
            return;
        }
        if (data.purchaseNotStarted) {
            return;
        }
        validationMessage.textContent = '';
        updateView(data)
    });
}

function purchase(product) {
    fetch('/purchase/' + product, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-Token': getCsrfToken()
        },
        body: JSON.stringify({ money: getMoney() })
    })
    .then(res => res.json())
    .then(data => updateView(data));
}

function closePurchase() {
    fetch('/close-purchase', {
        method: 'POST',
        headers: { 
            'X-CSRF-Token': getCsrfToken()
        },
    })
    .then(res => res.json())
    .then(data => updateView(data));
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}