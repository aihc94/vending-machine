const validationMessage = document.getElementById('validationMessage');

function getMoney() {
    return document.getElementById('money').value;
}

function updateView(data) {
    document.getElementById('identifier').textContent = data.identifier;
    document.getElementById('totalAmount').textContent = data.totalAmount;
    document.getElementById('restartPurchase').textContent = data.restartPurchase ? 'Yes' : 'No';
}

function addMoney() {
    console.log('AddMoneyCalled');
    fetch('/add-money', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ money: getMoney() })
    })
    .then(res => res.json())
    .then(data => {
        if (data.amountNotValid) {
            validationMessage.textContent = 'Amount not valid';
            return;
        }
        validationMessage.textContent = '';
        updateView(data)
    });
}

function purchase(product) {
    fetch('/purchase/' + product, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ money: getMoney() })
    })
    .then(res => res.json())
    .then(data => updateView(data));
}

function closePurchase() {
    fetch('/close-purchase', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => updateView(data));
}