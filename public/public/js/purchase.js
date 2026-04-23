const validationMessage = document.getElementById('validationMessage');
const validationPurchaseMessage = document.getElementById('validationPurchaseMessage');

function getMoney() {
    return document.getElementById('money').value;
}

function updateView(data) {
    document.getElementById('identifier').textContent = data.purchase.identifier;
    document.getElementById('totalAmount').textContent = data.purchase.totalAmount;
    document.getElementById('restartPurchase').textContent = data.purchase.restartPurchase ? 'Yes' : 'No';
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
        updateView(data);
    });
}

function purchase(product) {
    fetch('/purchase-product', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-Token': getCsrfToken()
        },
        body: JSON.stringify({ productCode: product })
    })
    .then(res => res.json())
    .then(data => {
        if (data.failedOnPurchase) {
            validationPurchaseMessage.textContent = data.message;
            return;
        }
        showModal(data);
    });
}

function closePurchase() {
    fetch('/close-purchase', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-Token': getCsrfToken()
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.failedOnPurchase) {
            validationPurchaseMessage.textContent = data.message;
            return;
        }
        showCloseModal(data);
    });
}

function showModal(data) {
    const modal = document.getElementById('purchaseModal');

    document.getElementById('modalProduct').textContent = JSON.stringify(data.product);

    document.getElementById('modalChange').textContent = JSON.stringify(data.change);

    document.getElementById('modalHistory').textContent = JSON.stringify(data.purchaseHistory, null, 2);

    modal.style.display = 'flex';
}

function showCloseModal(data) {
    const modal = document.getElementById('purchaseModal');

    document.getElementById('modalProduct').textContent = '';

    if (data.moneyForm === 'client') {
        document.getElementById('modalChange').textContent =
            JSON.stringify({
                message: 'Dinero insertado por el cliente devuelto',
                amount: data.returnAmounts
            });
    } else {
        document.getElementById('modalChange').textContent =
            JSON.stringify(data.returnAmounts);
    }

    document.getElementById('modalHistory').textContent =
        JSON.stringify(data.purchaseHistory, null, 2);

    modal.style.display = 'flex';
}

function reloadPage() {
    location.reload();
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}